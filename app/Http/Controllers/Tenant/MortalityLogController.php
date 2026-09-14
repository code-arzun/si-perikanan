<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\MortalityLogRequest;
use App\Models\Batch;
use App\Models\MortalityLog;
use Illuminate\Support\Facades\Auth;

class MortalityLogController extends Controller
{
    public function index()
    {
        $mortalityLogs = MortalityLog::with(['batch.pond', 'user'])
            ->latest('log_date')
            ->paginate(15);

        return view('tenant.logs.mortality.index', compact('mortalityLogs'));
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
}