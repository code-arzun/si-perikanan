<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CashflowCategoryController;
use App\Http\Controllers\Admin\FeedTypeController;
use App\Http\Controllers\Admin\FishSpeciesController;
use App\Http\Controllers\Admin\MasterRolePermissionController;
use App\Http\Controllers\Admin\PondTypeController;
use App\Http\Controllers\Admin\TenantManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Tenant\BatchController;
use App\Http\Controllers\Tenant\CashTransactionController;
use App\Http\Controllers\Tenant\ContactController;
use App\Http\Controllers\Tenant\DailyFeedLogController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\HarvestController;
use App\Http\Controllers\Tenant\MortalityLogController;
use App\Http\Controllers\Tenant\PondController;
use App\Http\Controllers\Tenant\SamplingLogController;
use App\Http\Controllers\Tenant\TenantProfileController;
use App\Http\Controllers\Tenant\TreatmentLogController;
use App\Http\Controllers\Tenant\UserController;
use App\Http\Controllers\Tenant\WaterQualityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    
    // Self-Service Signup / Registrasi Mandiri
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Auth Login (Wajib diberi ->name('login'))
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

});

Route::middleware(['role:superadmin'])->prefix('admin')->name('admin.')->group(function () {

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
    
    // --- Master Data 
    Route::resource('fish-species', FishSpeciesController::class)->except(['create', 'edit', 'show']);
    Route::resource('feed-types', FeedTypeController::class)->except(['create', 'edit', 'show']);
    Route::resource('pond-types', PondTypeController::class)->except(['create', 'edit', 'show']);
    Route::resource('cashflow-categories', CashflowCategoryController::class)->except(['create', 'edit', 'show']);

});

Route::middleware(['auth', 'tenant'])->prefix('tenant')->name('tenant.')->group(function () {

    // --- Akses Bersama (Semua Role Tenant Bisa Masuk) ---
    Route::middleware(['role:tenant_superadmin|tenant_admin_keuangan|tenant_staf_kolam'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        
        Route::controller(TenantProfileController::class)->prefix('profile')->name('profile.')->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::put('/', 'update')->name('update');
        });
    });

    // --- Khusus Operasional Tambak (Superadmin & Staf Kolam) ---
    Route::middleware(['role:tenant_superadmin|tenant_staf_kolam'])->group(function () {
        Route::resource('ponds', PondController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::resource('batches', BatchController::class)->only(['index', 'create', 'store', 'show']);
        Route::resource('harvests', HarvestController::class)->only(['index', 'create', 'store']);

        // Log Harian
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::controller(DailyFeedLogController::class)->prefix('feed')->name('feed.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/bulk', [DailyFeedLogController::class, 'bulkCreate'])->name('bulk-create');
                Route::post('/bulk', [DailyFeedLogController::class, 'bulkStore'])->name('bulk-store');
            });

            Route::controller(MortalityLogController::class)->prefix('mortality')->name('mortality.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/bulk', [MortalityLogController::class, 'bulkCreate'])->name('bulk-create');
                Route::post('/bulk', [MortalityLogController::class, 'bulkStore'])->name('bulk-store');
            });

            Route::controller(SamplingLogController::class)->prefix('sampling')->name('sampling.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/bulk', [SamplingLogController::class, 'bulkCreate'])->name('bulk-create');
                Route::post('/bulk', [SamplingLogController::class, 'bulkStore'])->name('bulk-store');
            });

            Route::controller(TreatmentLogController::class)->prefix('treatment')->name('treatment.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/bulk', [TreatmentLogController::class, 'bulkCreate'])->name('bulk-create');
                Route::post('/bulk', [TreatmentLogController::class, 'bulkStore'])->name('bulk-store');
            });

            Route::controller(WaterQualityLogController::class)->prefix('water')->name('water.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/bulk', [WaterQualityLogController::class, 'bulkCreate'])->name('bulk-create');
                Route::post('/bulk', [WaterQualityLogController::class, 'bulkStore'])->name('bulk-store');
            });
        });
    });

    // --- Khusus Keuangan & Kontak (Superadmin & Admin Keuangan) ---
    Route::middleware(['role:tenant_superadmin|tenant_admin_keuangan'])->group(function () {
        Route::resource('contacts', ContactController::class);
        Route::resource('finance', CashTransactionController::class);
    });

    // --- Khusus Manajemen Staf (Hanya Tenant Superadmin) ---
    Route::middleware(['role:tenant_superadmin'])->group(function () {
        Route::resource('employees', UserController::class)
            ->names('employees')
            ->parameters(['employees' => 'user'])
            ->except(['create', 'edit', 'show']);
    });

});

Route::post('/logout', [LoginController::class, 'logout'])
    ->withoutMiddleware([\App\Http\Middleware\EnsureTenantIsActive::class]);