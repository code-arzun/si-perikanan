<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MasterRolePermissionController;
use App\Http\Controllers\Admin\TenantManagementController;
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

Route::middleware('guest')->group(function () {
    
    // Self-Service Signup / Registrasi Mandiri
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Auth Login (Wajib diberi ->name('login'))
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

});

Route::middleware(['auth', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {

    // --- Dashboard ---
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // --- Manajemen Tenant (CRUD) ---
    Route::resource('tenants', TenantManagementController::class);
    Route::prefix('tenants/{tenant}')->name('tenants.')->group(function () {
        Route::get('/ponds', [TenantManagementController::class, 'ponds'])->name('ponds');
        Route::get('/batches', [TenantManagementController::class, 'batches'])->name('batches');
        Route::get('/users', [TenantManagementController::class, 'users'])->name('users');
        Route::get('/harvests', [TenantManagementController::class, 'harvests'])->name('harvests');
    });

    // --- Master Roles & Permissions ---
    Route::controller(MasterRolePermissionController::class)->group(function () {
        
        // Roles & Permission Index
        Route::get('/roles', 'index')->name('roles.index');

        // Permission Actions
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::post('/', 'storePermission')->name('store');
            Route::put('/{permission}', 'updatePermission')->name('update');
            Route::delete('/{permission}', 'destroyPermission')->name('destroy');
        });

        // Role Actions & Matrix
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::post('/', 'storeRole')->name('store');
            Route::put('/{role}', 'updateRole')->name('update');
            Route::delete('/{role}', 'destroyRole')->name('destroy');
            Route::put('/{role}/permissions', 'updateRolePermissions')->name('updatePermissions');
        });

    });

});

Route::middleware(['auth', 'tenant.active'])->prefix('tenant')->name('tenant.')->group(function () {

    // --- Core Workspace ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // --- Profil Usaha ---
    Route::controller(TenantProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
    });

    // --- Operasional Tambak ---
    Route::resource('ponds', PondController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('batches', BatchController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('harvests', HarvestController::class)->only(['index', 'create', 'store']);

    // --- Log Harian (Operational Logs Group) ---
    Route::prefix('logs')->name('logs.')->group(function () {
        
        // Pakan
        Route::controller(DailyFeedLogController::class)->prefix('feed')->name('feed.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        // Kematian
        Route::controller(MortalityLogController::class)->prefix('mortality')->name('mortality.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        // Sampling Pertumbuhan
        Route::controller(SamplingLogController::class)->prefix('sampling')->name('sampling.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        // Treatment & Obat
        Route::controller(TreatmentLogController::class)->prefix('treatment')->name('treatment.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        // Kualitas Air
        Route::controller(WaterQualityLogController::class)->prefix('water')->name('water.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

    });

});

Route::post('/logout', [LoginController::class, 'logout'])
    ->withoutMiddleware([\App\Http\Middleware\EnsureTenantIsActive::class]);