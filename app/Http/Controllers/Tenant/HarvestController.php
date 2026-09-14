<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\HarvestRequest;
use App\Models\Batch;
use App\Models\HarvestLog;
use App\Models\Pond;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HarvestController extends Controller
{
    public function index()
    {
        $harvestLogs = HarvestLog::with(['batch.pond', 'user'])
            ->latest('harvest_date')
            ->paginate(15);

        return view('tenant.harvests.index', compact('harvestLogs'));
    }

    public function create()
    {
        // Ambil siklus aktif beserta akumulasi kematian & panen parsial
        $activeBatches = Batch::with('pond')
            ->withSum('mortalityLog', 'quantity_pcs')
            ->withSum('harvestLog', 'total_pcs')
            ->where('status', 'aktif')
            ->get();

        return view('tenant.harvests.create', compact('activeBatches'));
    }

    public function store(HarvestRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validated = $request->validated();
            
            // 1. Hitung Total Revenue
            $totalRevenue = 0;
            if (!empty($validated['price_per_kg']) && $validated['price_per_kg'] > 0) {
                $totalRevenue = $validated['weight_kg'] * $validated['price_per_kg'];
            }

            // 2. Hitung Otomatis total_pcs dari Size jika dikosongkan
            if (empty($validated['total_pcs']) && !empty($validated['fish_size']) && $validated['fish_size'] > 0) {
                $validated['total_pcs'] = round($validated['weight_kg'] * $validated['fish_size']);
            }

            // 3. Simpan Log Panen
            HarvestLog::create(array_merge($validated, [
                'total_revenue' => $totalRevenue,
                'logged_by'     => Auth::id(),
            ]));

            // 4. Jika Panen Total, Tutup Siklus & Kosongkan Kolam
            if ($validated['harvest_type'] === 'total') {
                $batch = Batch::findOrFail($validated['batch_id']);

                // Akumulasi total berat seluruh panen (parsial + total)
                $totalHarvestWeightKg = HarvestLog::where('batch_id', $batch->id)->sum('weight_kg');

                $batch->update([
                    'status'                   => 'selesai',
                    'actual_harvest_date'      => $validated['harvest_date'],
                    'actual_harvest_weight_kg' => $totalHarvestWeightKg,
                ]);

                // Kosongkan kolam agar siap dipakai siklus baru
                Pond::where('id', $batch->pond_id)->update(['status' => 'kosong']);
            }
        });

        return redirect('/tenant/harvests')->with('success', 'Catatan panen berhasil disimpan!');
    }
}