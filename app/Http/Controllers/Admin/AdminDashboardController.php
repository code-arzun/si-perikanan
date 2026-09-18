<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Metrik Utama Platform
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', 'active')->count();
        $suspendedTenants = Tenant::where('status', '!=', 'active')->count();
        $totalUsers = User::role('tenant_superadmin')->count();

        // 2. Tenant Terbaru
        $latestTenants = Tenant::withCount('users')->latest()->take(5)->get();

        // 3. Pendaftaran Tenant Bulan Ini
        $newTenantsThisMonth = Tenant::whereMonth('created_at', Carbon::now()->month)->count();

        return view('admin.dashboard.index', compact(
            'totalTenants',
            'activeTenants',
            'suspendedTenants',
            'totalUsers',
            'latestTenants',
            'newTenantsThisMonth'
        ));
    }
}