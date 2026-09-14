<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\WaterQualityLogRequest;
use App\Models\Batch;
use App\Models\WaterQualityLog;
use Illuminate\Support\Facades\Auth;

class WaterQualityLogController extends Controller
{
    public function index()
    {
        $waterLogs = WaterQualityLog::with(['batch.pond', 'user'])
            ->latest('check_date')
            ->paginate(15);

        return view('tenant.logs.water.index', compact('waterLogs'));
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
}