<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\TreatmentLogBulkRequest;
use App\Http\Requests\Tenant\TreatmentLogRequest;
use App\Models\Batch;
use App\Models\TreatmentLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TreatmentLogController extends Controller
{
    public function index()
    {
        $activeBatches = Batch::with(['pond', 'treatmentLog' => function ($query) {
                $query->orderBy('treatment_date', 'desc')->orderBy('created_at', 'desc');
            }, 'treatmentLog.user'])
            ->where('status', 'aktif')
            ->get()
            ->map(function ($batch) {
                // Total Frekuensi Aplikan Treatment
                $batch->total_treatments = $batch->treatmentLog->count();
                
                // Catatan Treatment Terakhir
                $batch->latest_log = $batch->treatmentLog->first();

                return $batch;
            });

    return view('tenant.logs.treatment.index', compact('activeBatches'));
    }

    public function create()
    {
        $activeBatches = Batch::with('pond')->where('status', 'aktif')->get();

        return view('tenant.logs.treatment.create', compact('activeBatches'));
    }

    public function store(TreatmentLogRequest $request)
    {
        TreatmentLog::create(array_merge($request->validated(), [
            'logged_by' => Auth::id(),
        ]));

        return redirect('/tenant/logs/treatment')->with('success', 'Catatan treatment & obat berhasil disimpan!');
    }

    public function bulkCreate()
    {
        $activeBatches = Batch::with('pond')
            ->where('status', 'aktif')
            ->get();

        return view('tenant.logs.treatment.bulk', compact('activeBatches'));
    }

    public function bulkStore(TreatmentLogBulkRequest $request)
    {
        $validated     = $request->validated();
        $treatmentDate = $validated['treatment_date'];
        $tenantId      = auth()->user()->tenant_id;
        $userId        = auth()->id();

        DB::transaction(function () use ($validated, $treatmentDate, $tenantId, $userId) {
            foreach ($validated['logs'] as $logData) {
                if (!empty($logData['product_name']) && !empty($logData['dosage_amount'])) {
                    TreatmentLog::create([
                        'tenant_id'      => $tenantId,
                        'batch_id'       => $logData['batch_id'],
                        'logged_by'      => $userId,
                        'treatment_date' => $treatmentDate,
                        'product_name'   => $logData['product_name'],
                        'dosage_amount'  => $logData['dosage_amount'],
                        'dosage_unit'    => $logData['dosage_unit'],
                        'purpose'        => $logData['purpose'] ?? null,
                        'notes'          => $logData['notes'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('tenant.logs.treatment.index')
            ->with('success', 'Catatan treatment massal berhasil disimpan.');
    }
}