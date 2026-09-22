<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SamplingLogBulkRequest;
use App\Http\Requests\Tenant\SamplingLogRequest;
use App\Models\Batch;
use App\Models\SamplingLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SamplingLogController extends Controller
{
    public function index()
    {
        // $samplingLogs = SamplingLog::with(['batch.pond', 'user'])
        //     ->latest('sampling_date')
        //     ->paginate(15);

        // return view('tenant.logs.sampling.index', compact('samplingLogs'));

        $activeBatches = Batch::with(['pond', 'samplingLog' => function ($query) {
                $query->orderBy('sampling_date', 'desc')->orderBy('created_at', 'desc');
            }, 'samplingLog.user'])
            ->where('status', 'aktif')
            ->get()
            ->map(function ($batch) {
                // Ambil data sampling terakhir untuk statistik di Header Card
                $batch->latest_log = $batch->samplingLog->first();

                return $batch;
            });

        return view('tenant.logs.sampling.index', compact('activeBatches'));
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

    public function bulkCreate()
    {
        $activeBatches = Batch::with('pond')
            ->where('status', 'aktif')
            ->get();

        return view('tenant.logs.sampling.bulk', compact('activeBatches'));
    }

    public function bulkStore(SamplingLogBulkRequest $request)
    {
        $validated    = $request->validated();
        $samplingDate = $validated['sampling_date'];
        $tenantId     = auth()->user()->tenant_id;
        $userId       = auth()->id();

        DB::transaction(function () use ($validated, $samplingDate, $tenantId, $userId) {
            foreach ($validated['logs'] as $logData) {
                // Simpan jika jumlah sampel dan MBW/Bobot Rata-rata terisi dengan valid
                if (!empty($logData['sample_count_pcs']) && !empty($logData['avg_weight_g'])) {
                    SamplingLog::create([
                        'tenant_id'            => $tenantId,
                        'batch_id'             => $logData['batch_id'],
                        'logged_by'            => $userId,
                        'sampling_date'        => $samplingDate,
                        'sample_count_pcs'     => $logData['sample_count_pcs'],
                        'avg_weight_g'         => $logData['avg_weight_g'],
                        'avg_length_cm'        => $logData['avg_length_cm'] ?? null,
                        'estimated_biomass_kg' => $logData['estimated_biomass_kg'] ?? null,
                        'notes'                => $logData['notes'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('tenant.logs.sampling.index')
            ->with('success', 'Catatan sampling pertumbuhan massal berhasil disimpan.');
    }
}