<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\DailyFeedLog;
use App\Models\HarvestLog;
use App\Models\Pond;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Stat Status Kolam
        $totalPondsCount = Pond::count();
        $activePondsCount = Pond::where('status', 'aktif')->count();
        $emptyPondsCount = Pond::where('status', 'kosong')->count();

        // 2. Ambil seluruh Siklus Aktif beserta akumulasi Log-nya
        $activeBatches = Batch::with(['pond', 'fishSpecies'])
            ->withSum('mortalityLog', 'quantity_pcs')
            ->withSum('harvestLog', 'total_pcs')
            ->where('status', 'aktif')
            ->get();

        // 3. Hitung Total Biomasa & Populasi Aktif di Seluruh Tambak
        $totalActiveBiomassKg = 0;
        $totalActivePopulationPcs = 0;

        foreach ($activeBatches as $batch) {
            $totalMati = $batch->mortality_log_sum_quantity_pcs ?? 0;
            $totalParsial = $batch->harvest_log_sum_total_pcs ?? 0;
            $sisaPopulasi = max(0, $batch->initial_seed_count - $totalMati - $totalParsial);

            $latestSampling = $batch->samplingLog()->latest('sampling_date')->first();
            $latestMbwG = $latestSampling ? $latestSampling->avg_weight_g : $batch->initial_avg_weight_g;

            $biomassKg = ($sisaPopulasi * $latestMbwG) / 1000;

            $batch->calculated_population = $sisaPopulasi;
            $batch->calculated_mbw = $latestMbwG;
            $batch->calculated_biomass = round($biomassKg, 2);

            $totalActivePopulationPcs += $sisaPopulasi;
            $totalActiveBiomassKg += $biomassKg;
        }

        // 4. Statistik Pakan 7 Hari Terakhir
        $sevenDaysAgo = Carbon::now()->subDays(6)->format('Y-m-d');
        $feedStatsLast7Days = DailyFeedLog::selectRaw('feed_date, SUM(amount_kg) as total_kg')
            ->where('feed_date', '>=', $sevenDaysAgo)
            ->groupBy('feed_date')
            ->orderBy('feed_date', 'ASC')
            ->get();

        $totalFeedTodayKg = DailyFeedLog::whereDate('feed_date', Carbon::today())->sum('amount_kg') ?? 0;
        $totalFeedThisMonthKg = DailyFeedLog::whereMonth('feed_date', Carbon::now()->month)->sum('amount_kg') ?? 0;

        // 5. Total Pendapatan Panen Bulan Ini
        $totalRevenueThisMonth = HarvestLog::whereMonth('harvest_date', Carbon::now()->month)->sum('total_revenue') ?? 0;

        return view('tenant.dashboard.index', compact(
            'totalPondsCount',
            'activePondsCount',
            'emptyPondsCount',
            'activeBatches',
            'totalActiveBiomassKg',
            'totalActivePopulationPcs',
            'totalFeedTodayKg',
            'totalFeedThisMonthKg',
            'totalRevenueThisMonth',
            'feedStatsLast7Days'
        ));
    }
}