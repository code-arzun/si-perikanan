<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DailyFeedLogRequest;
use App\Models\Batch;
use App\Models\DailyFeedLog;
use App\Models\FeedType;
use Illuminate\Support\Facades\Auth;

class DailyFeedLogController extends Controller
{
    public function index()
    {
        $feedLogs = DailyFeedLog::with(['batch.pond', 'feedType', 'user'])
            ->latest('feed_date')
            ->latest('feed_time')
            ->paginate(15);

        return view('tenant.logs.feed.index', compact('feedLogs'));
    }

    public function create()
    {
        $activeBatches = Batch::with('pond')->where('status', 'aktif')->get();
        $feedTypes = FeedType::all();

        return view('tenant.logs.feed.create', compact('activeBatches', 'feedTypes'));
    }

    public function store(DailyFeedLogRequest $request)
    {
        DailyFeedLog::create(array_merge($request->validated(), [
            'logged_by' => Auth::id(),
        ]));

        return redirect('/tenant/logs/feed')->with('success', 'Pemberian pakan harian berhasil dicatat!');
    }
}