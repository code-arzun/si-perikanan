<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DailyFeedLogBulkRequest;
use App\Http\Requests\Tenant\DailyFeedLogRequest;
use App\Models\Batch;
use App\Models\DailyFeedLog;
use App\Models\FeedType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailyFeedLogController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');

        $activeBatches = Batch::with(['pond', 'dailyFeedLog.feedType', 'dailyFeedLog.user'])
            ->where('status', 'aktif')
            ->get()
            ->map(function ($batch) use ($today) {
                $batch->today_feed_kg = $batch->dailyFeedLog
                    ->filter(fn($log) => \Carbon\Carbon::parse($log->feed_date)->format('Y-m-d') === $today)
                    ->sum('amount_kg');

                $batch->total_feed_kg = $batch->dailyFeedLog->sum('amount_kg');
                $batch->last_feed = $batch->dailyFeedLog->sortByDesc('feed_date')->first();

                return $batch;
            });

        $feedTypes = FeedType::all();

        return view('tenant.logs.feed.index', compact('activeBatches', 'feedTypes'));
    }

    public function create(Request $request)
    {
        $activeBatches = Batch::with('pond')->where('status', 'aktif')->get();
        $feedTypes = FeedType::all();

        // Ambil selected_batch_id jika dikirim dari tombol index
        $selectedBatchId = $request->query('batch_id');

        return view('tenant.logs.feed.create', compact('activeBatches', 'feedTypes', 'selectedBatchId'));
    }

    public function store(DailyFeedLogRequest $request)
    // {
    //     DailyFeedLog::create(array_merge($request->validated(), [
    //         'logged_by' => Auth::id(),
    //     ]));

    //     return redirect('/tenant/logs/feed')->with('success', 'Pemberian pakan harian berhasil dicatat!');
    // }
    {
        $validated = $request->validated();
        $validated['logged_by'] = auth()->id();

        DailyFeedLog::create($validated);

        return redirect()->route('tenant.logs.feed.index')
            ->with('success', 'Catatan pakan harian berhasil disimpan.');
    }

    public function bulkCreate()
    {
        $activeBatches = Batch::with('pond')->where('status', 'aktif')->get();
        $feedTypes = FeedType::all();

        return view('tenant.logs.feed.bulk', compact('activeBatches', 'feedTypes'));
    }

    public function bulkStore(DailyFeedLogBulkRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            foreach ($validated['logs'] as $logData) {
                // Abaikan jika jumlah pakan 0/kosong
                if (empty($logData['amount_kg']) || $logData['amount_kg'] <= 0) {
                    continue;
                }

                DailyFeedLog::create([
                    'tenant_id'         => auth()->user()->tenant_id,
                    'batch_id'          => $logData['batch_id'],
                    'feed_type_id'      => $validated['feed_type_id'] ?? null,
                    'logged_by'         => auth()->id(),
                    'feed_date'         => $validated['feed_date'],
                    'feeding_frequency' => $validated['feeding_frequency'],
                    'amount_per_feed_g' => $logData['amount_per_feed_g'] ?? null,
                    'amount_kg'         => $logData['amount_kg'],
                    'appetite_response' => $logData['appetite_response'] ?? 'baik',
                    'notes'             => $logData['notes'] ?? null,
                ]);
            }
        });

        return redirect()->route('tenant.logs.feed.index')
            ->with('success', 'Berhasil mencatat pakan massal untuk beberapa kolam sekaligus!');
    }
}