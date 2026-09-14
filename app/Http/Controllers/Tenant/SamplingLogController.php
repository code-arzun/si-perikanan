<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SamplingLogRequest;
use App\Models\Batch;
use App\Models\SamplingLog;
use Illuminate\Support\Facades\Auth;

class SamplingLogController extends Controller
{
    public function index()
    {
        $samplingLogs = SamplingLog::with(['batch.pond', 'user'])
            ->latest('sampling_date')
            ->paginate(15);

        return view('tenant.logs.sampling.index', compact('samplingLogs'));
    }

    public function create()
    {
        $activeBatches = Batch::with('pond')
            ->withSum('mortalityLog', 'quantity_pcs')
            ->where('status', 'aktif')
            ->get()
            ->map(function ($batch) {
                $totalMati = $batch->mortality_log_sum_quantity_pcs ?? 0;
                $batch->current_population = max(0, $batch->initial_seed_count - $totalMati);
                return $batch;
            });

        return view('tenant.logs.sampling.create', compact('activeBatches'));
    }

    public function store(SamplingLogRequest $request)
    {
        $validated = $request->validated();

        // Otomatis hitung biomasa jika dikosongkan pengguna
        if (empty($validated['estimated_biomass_kg'])) {
            $batch = Batch::withSum('mortalityLog', 'quantity_pcs')->findOrFail($request->batch_id);

            // 1. Hitung sisa populasi hidup (Tebar Awal - Akumulasi Kematian)
            $totalMati = $batch->mortality_log_sum_quantity_pcs ?? 0;
            $sisaPopulasi = max(0, $batch->initial_seed_count - $totalMati);

            // 2. Hitung biomasa (Kg) = (Sisa Populasi * MBW Gram) / 1000
            $validated['estimated_biomass_kg'] = round(($sisaPopulasi * $request->avg_weight_g) / 1000, 2);
        }

        SamplingLog::create(array_merge($validated, [
            'logged_by' => Auth::id(),
        ]));

        return redirect('/tenant/logs/sampling')->with('success', 'Catatan sampling pertumbuhan berhasil disimpan!');
    }
}