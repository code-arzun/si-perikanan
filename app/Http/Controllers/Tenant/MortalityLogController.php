<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\MortalityLogBulkRequest;
use App\Http\Requests\Tenant\MortalityLogRequest;
use App\Models\Batch;
use App\Models\MortalityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MortalityLogController extends Controller
{
    public function index()
    {
        // $mortalityLogs = MortalityLog::with(['batch.pond', 'user'])
        //     ->latest('log_date')
        //     ->paginate(15);

        // return view('tenant.logs.mortality.index', compact('mortalityLogs'));

        $activeBatches = Batch::with(['pond', 'mortalityLog' => function ($query) {
                $query->orderBy('log_date', 'desc')->orderBy('created_at', 'desc');
            }, 'mortalityLog.user'])
            ->where('status', 'aktif')
            ->get()
            ->map(function ($batch) {
                // Agregasi Total Kematian Siklus Ini
                $batch->total_death_pcs = $batch->mortalityLog->sum('quantity_pcs');
                $batch->total_death_g  = $batch->mortalityLog->sum('total_weight_g');
                
                // Catatan Kematian Terakhir
                $batch->latest_log = $batch->mortalityLog->first();

                return $batch;
            });

        return view('tenant.logs.mortality.index', compact('activeBatches'));
    }

    public function create()
    {
        $activeBatches = Batch::with('pond')->where('status', 'aktif')->get();

        return view('tenant.logs.mortality.create', compact('activeBatches'));
    }

    public function store(MortalityLogRequest $request)
    {
        MortalityLog::create(array_merge($request->validated(), [
            'logged_by' => Auth::id(),
        ]));

        return redirect('/tenant/logs/mortality')->with('success', 'Catatan kematian ikan berhasil disimpan!');
    }

    public function bulkCreate()
    {
        $activeBatches = Batch::with('pond')
            ->where('status', 'aktif')
            ->get();

        return view('tenant.logs.mortality.bulk', compact('activeBatches'));
    }

    public function bulkStore(MortalityLogBulkRequest $request)
    {
        $validated = $request->validated();
        $logDate   = $validated['log_date'];
        $tenantId  = auth()->user()->tenant_id;
        $userId    = auth()->id();

        DB::transaction(function () use ($validated, $logDate, $tenantId, $userId) {
            foreach ($validated['logs'] as $logData) {
                // Simpan hanya jika jumlah mati (quantity_pcs) diisi dan > 0
                if (!empty($logData['quantity_pcs']) && $logData['quantity_pcs'] > 0) {
                    MortalityLog::create([
                        'tenant_id'      => $tenantId,
                        'batch_id'       => $logData['batch_id'],
                        'logged_by'      => $userId,
                        'log_date'       => $logDate,
                        'quantity_pcs'   => $logData['quantity_pcs'],
                        'total_weight_g' => $logData['total_weight_g'] ?? null,
                        'indication'     => $logData['indication'] ?? null,
                        'action_taken'   => $logData['action_taken'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('tenant.logs.mortality.index')
            ->with('success', 'Catatan kematian ikan massal berhasil disimpan.');
    }
}