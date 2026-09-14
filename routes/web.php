<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Tenant\BatchController;
use App\Http\Controllers\Tenant\DailyFeedLogController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\HarvestController;
use App\Http\Controllers\Tenant\MortalityLogController;
use App\Http\Controllers\Tenant\PondController;
use App\Http\Controllers\Tenant\SamplingLogController;
use App\Http\Controllers\Tenant\TenantProfileController;
use App\Http\Controllers\Tenant\TreatmentLogController;
use App\Http\Controllers\Tenant\WaterQualityLogController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
    // return view('welcome');
// });

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', function () { return view('auth.register'); });
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', function () { return view('auth.login'); });
    Route::post('/login', [LoginController::class, 'login']);
});

// Route::middleware('auth')->group(function () {
//     Route::get('/tenant/dashboard', function () { return view('tenant.dashboard'); });
//     Route::get('/saas/dashboard', function () { return view('tenant.dashboard'); });
//     Route::post('/logout', [LoginController::class, 'logout']);
// });

Route::middleware('auth')->group(function () {
    // Route::get('/dashboard', function () {
    //     return auth()->user()->hasRole('saas_admin') ? redirect('/saas/dashboard') : redirect('/tenant/dashboard');
    // });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/tenant/dashboard', [DashboardController::class, 'index']);

    Route::get('/tenant/dashboard', function () { return view('tenant.dashboard'); });
    Route::get('/saas/dashboard', function () { return view('tenant.dashboard'); });
    Route::post('/logout', [LoginController::class, 'logout']);

    // Route Manajemen Kolam Stage 2
    Route::get('/tenant/ponds', [PondController::class, 'index']);
    Route::get('/tenant/ponds/create', [PondController::class, 'create']);
    Route::post('/tenant/ponds', [PondController::class, 'store']);
    Route::delete('/tenant/ponds/{pond}', [PondController::class, 'destroy']);

    // Route Siklus Budidaya (Batch)
    Route::get('/tenant/batches', [BatchController::class, 'index']);
    Route::get('/tenant/batches/create', [BatchController::class, 'create']);
    Route::post('/tenant/batches', [BatchController::class, 'store']);
    Route::get('/tenant/batches/{batch}', [BatchController::class, 'show']);

    // Route Profil Usaha
    Route::get('/tenant/profile', [TenantProfileController::class, 'edit']);
    Route::put('/tenant/profile', [TenantProfileController::class, 'update']);

    // Route Log Pakan
    Route::get('/tenant/logs/feed', [DailyFeedLogController::class, 'index']);
    Route::get('/tenant/logs/feed/create', [DailyFeedLogController::class, 'create']);
    Route::post('/tenant/logs/feed', [DailyFeedLogController::class, 'store']);

    // Route Log Kematian
    Route::get('/tenant/logs/mortality', [MortalityLogController::class, 'index']);
    Route::get('/tenant/logs/mortality/create', [MortalityLogController::class, 'create']);
    Route::post('/tenant/logs/mortality', [MortalityLogController::class, 'store']);
    
    // Route Sampling Pertumbuhan
    Route::get('/tenant/logs/sampling', [SamplingLogController::class, 'index']);
    Route::get('/tenant/logs/sampling/create', [SamplingLogController::class, 'create']);
    Route::post('/tenant/logs/sampling', [SamplingLogController::class, 'store']);

    // Route Treatment & Obat
    Route::get('/tenant/logs/treatment', [TreatmentLogController::class, 'index']);
    Route::get('/tenant/logs/treatment/create', [TreatmentLogController::class, 'create']);
    Route::post('/tenant/logs/treatment', [TreatmentLogController::class, 'store']);

    // Route Kualitas Air
    Route::get('/tenant/logs/water', [WaterQualityLogController::class, 'index']);
    Route::get('/tenant/logs/water/create', [WaterQualityLogController::class, 'create']);
    Route::post('/tenant/logs/water', [WaterQualityLogController::class, 'store']);
    
    // Route Panen Management
    Route::get('/tenant/harvests', [HarvestController::class, 'index']);
    Route::get('/tenant/harvests/create', [HarvestController::class, 'create']);
    Route::post('/tenant/harvests', [HarvestController::class, 'store']);
});