<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\WaterQualityLogBulkRequest;
use App\Http\Requests\Tenant\WaterQualityLogRequest;
use App\Models\Batch;
use App\Models\WaterQualityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WaterQualityLogController extends Controller
{
    public function index()
    {
        // $waterLogs = WaterQualityLog::with(['batch.pond', 'user'])
        //     ->latest('check_date')
        //     ->paginate(15);

        // return view('tenant.logs.water.index', compact('waterLogs'));

        // $activeBatches = Batch::with(['pond', 'waterQualityLog.user'])
        //     ->where('status', 'aktif')
        //     ->get()
        //     ->map(function ($batch) {
        //         // Urutkan riwayat dari yang paling baru secara aman
        //             $logs = $batch->waterQualityLogs ?? collect();
                    
        //             $batch->sorted_logs = $logs->sortByDesc('check_date');
        //             $batch->latest_log  = $batch->sorted_logs->first();

        //         return $batch;
        //     });

        $activeBatches = Batch::with(['pond', 'waterQualityLog' => function ($query) {
                // Urutkan riwayat langsung dari Database dari yang terbaru
                $query->orderBy('check_date', 'desc')->orderBy('created_at', 'desc');
            }, 'waterQualityLog.user']) // Pastikan relasi user di-load jika ada
            ->where('status', 'aktif')
            ->get()
            ->map(function ($batch) {
                // Ambil data log terakhir untuk statistik di Card Header
                $batch->latest_log = $batch->waterQualityLog->first();

                return $batch;
            });

        return view('tenant.logs.water.index', compact('activeBatches'));
    }

    public function create()
    {
        $activeBatches = Batch::with('pond')->where('status', 'aktif')->get();

        return view('tenant.logs.water.create', compact('activeBatches'));
    }

    public function store(WaterQualityLogRequest $request)
    {
        WaterQualityLog::create(array_merge($request->validated(), [
            'logged_by' => Auth::id(),
        ]));

        return redirect('/tenant/logs/water')->with('success', 'Catatan kualitas air berhasil disimpan!');
    }

    public function bulkCreate()
    {
        $activeBatches = Batch::with('pond')
            ->where('status', 'aktif')
            ->get();

        return view('tenant.logs.water.bulk', compact('activeBatches'));
    }

    public function bulkStore(WaterQualityLogBulkRequest $request)
    {
        $validated = $request->validated();
        $checkDate = $validated['check_date'];
        $session   = $validated['check_time_session'];
        $tenantId  = auth()->user()->tenant_id;
        $userId    = auth()->id();

        DB::transaction(function () use ($validated, $checkDate, $session, $tenantId, $userId) {
            foreach ($validated['logs'] as $logData) {
                // Cek apakah ada minimal 1 parameter yang diisi agar tidak menyimpan baris kosong
                $hasContent = !empty($logData['ph']) || 
                            !empty($logData['do_mg_l']) || 
                            !empty($logData['temperature_c']) || 
                            !empty($logData['transparency_cm']) || 
                            !empty($logData['water_color']) || 
                            !empty($logData['notes']);

                if ($hasContent) {
                    WaterQualityLog::create([
                        'tenant_id'          => $tenantId,
                        'batch_id'           => $logData['batch_id'],
                        'logged_by'          => $userId,
                        'check_date'         => $checkDate,
                        'check_time_session' => $session,
                        'ph'                 => $logData['ph'] ?? null,
                        'do_mg_l'            => $logData['do_mg_l'] ?? null,
                        'temperature_c'      => $logData['temperature_c'] ?? null,
                        'transparency_cm'    => $logData['transparency_cm'] ?? null,
                        'water_color'        => $logData['water_color'] ?? null,
                        'notes'              => $logData['notes'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('tenant.logs.water.index')
            ->with('success', 'Catatan kualitas air massal berhasil disimpan.');
    }
}