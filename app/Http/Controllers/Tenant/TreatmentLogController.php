<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\TreatmentLogRequest;
use App\Models\Batch;
use App\Models\TreatmentLog;
use Illuminate\Support\Facades\Auth;

class TreatmentLogController extends Controller
{
    public function index()
    {
        $treatmentLogs = TreatmentLog::with(['batch.pond', 'user'])
            ->latest('treatment_date')
            ->paginate(15);

        return view('tenant.logs.treatment.index', compact('treatmentLogs'));
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
}