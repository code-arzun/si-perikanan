<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\BatchRequest;
use App\Models\Batch;
use App\Models\FishSpecies;
use App\Models\Pond;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::with(['pond', 'fishSpecies'])
        ->withSum('mortalityLog', 'quantity_pcs')
        ->withSum('harvestLog', 'total_pcs')
        ->latest()->get();

        return view('tenant.batches.index', compact('batches'));
    }

    public function create()
    {
        // Ambil kolam yang sedang kosong (belum ada siklus aktif)
        $ponds = Pond::where('status', 'kosong')->get();
        $fishSpecies = FishSpecies::all();

        return view('tenant.batches.create', compact('ponds', 'fishSpecies'));
    }

    public function store(BatchRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Hitung biomasa awal (Kg) = (Jumlah Ekor * Gram) / 1000
            $totalWeightKg = ($request->initial_seed_count * $request->initial_avg_weight_g) / 1000;

            // 1. Simpan data Batch baru
            $batch = Batch::create(array_merge($request->validated(), [
                'initial_total_weight_kg' => $totalWeightKg,
                'status'                  => 'aktif',
            ]));

            // 2. Update status Kolam menjadi 'aktif'
            Pond::where('id', $request->pond_id)->update(['status' => 'aktif']);
        });

        return redirect('/tenant/batches')->with('success', 'Siklus budidaya berhasil dimulai!');
    }

    public function show(Batch $batch)
{
    // Load relasi log
    $batch->load([
        'pond',
        'fishSpecies',
        'dailyFeedLog' => fn($q) => $q->latest('feed_date')->take(10),
        'mortalityLog'  => fn($q) => $q->latest('log_date')->take(10),
        'samplingLog'  => fn($q) => $q->latest('sampling_date')->take(10),
        'harvestLog'    => fn($q) => $q->latest('harvest_date'),
    ]);

    // 1. Akumulasi Log
    $totalFeedKg = $batch->dailyFeedLog()->sum('amount_kg') ?? 0;
    $totalMortalityPcs = $batch->mortalityLog()->sum('quantity_pcs') ?? 0;
    $totalHarvestPcs = $batch->harvestLog()->sum('total_pcs') ?? 0;
    $totalHarvestKg = $batch->harvestLog()->sum('weight_kg') ?? 0;
    $totalRevenue = $batch->harvestLog()->sum('total_revenue') ?? 0;

    // 2. Sisa Populasi Aktif di Kolam (Tebar - Mati - Ekor Terpanen Parsial)
    $currentPopulation = max(0, $batch->initial_seed_count - $totalMortalityPcs - $totalHarvestPcs);

    // 3. Survival Rate (SR %) = ((Sisa Populasi + Total Ekor Panen) / Tebar Awal) * 100
    $survivalRate = $batch->initial_seed_count > 0 
        ? round((($currentPopulation + $totalHarvestPcs) / $batch->initial_seed_count) * 100, 1) 
        : 0;

    // 4. Data Sampling & Estimasi Biomasa AKTIF Saat Ini (Hanya Ikan yang Masih di Kolam)
    $latestSampling = $batch->samplingLog()->latest('sampling_date')->first();
    $latestMbwG = $latestSampling ? $latestSampling->avg_weight_g : $batch->initial_avg_weight_g;
    
    // Biomasa aktif di kolam saat ini (Kg)
    $currentBiomassKg = round(($currentPopulation * $latestMbwG) / 1000, 2);

    // 5. Kalkulasi FCR (Feed Ratio)
    // Total Biomasa Dihasilkan = Biomasa Ikan di Kolam + Total Kg Ikan yang Sudah Dipanen
    $totalBiomassProducedKg = $currentBiomassKg + $totalHarvestKg;
    $biomassGainKg = $totalBiomassProducedKg - $batch->initial_total_weight_kg;
    $currentFcr = ($biomassGainKg > 0) ? round($totalFeedKg / $biomassGainKg, 2) : 0;

    return view('tenant.batches.show', compact(
        'batch',
        'totalFeedKg',
        'totalMortalityPcs',
        'totalHarvestPcs',
        'totalHarvestKg',
        'totalRevenue',
        'currentPopulation',
        'survivalRate',
        'latestMbwG',
        'currentBiomassKg',
        'currentFcr'
    ));
}
}