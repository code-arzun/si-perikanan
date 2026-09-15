<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantManagementController extends Controller
{
    public function index()
    {
        $tenants = Tenant::withCount('users')->latest()->paginate(10);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'     => ['required', 'string', 'max:255', 'unique:users,username'],
            'owner_name'   => ['required', 'string', 'max:255'],
            'owner_phone'  => ['required', 'string', 'max:20', 'unique:users,phone'],
            'owner_email'  => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'password'     => ['required', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($validated) {
            // $tenantName = $validated['company_name'] ?: ('Tambak ' . $validated['owner_name']);
            
            // Tentukan tenant_type berdasarkan ketersediaan company_name (Sesuai ENUM DB: individual / corporate)
            $tenantType = !empty($validated['company_name']) ? 'corporate' : 'individual';

            $tenant = Tenant::create([
                'phone_or_email' => $validated['owner_phone'],
                'status'         => 'active',
                'tenant_type'    => $tenantType, // Nilai enum yang valid di DB
            ]);

            $owner = User::create([
                'tenant_id' => $tenant->id,
                'username'  => $validated['username'],
                'name'      => $validated['owner_name'],
                'phone'     => $validated['owner_phone'],
                'email'     => $validated['owner_email'] ?? null,
                'password'  => Hash::make($validated['password']),
                'role'      => 'owner',
            ]);

            if (\Spatie\Permission\Models\Role::where('name', 'Owner')->exists()) {
                $owner->assignRole('Owner');
            }
        });

        return redirect('/admin/tenants')->with('success', 'Tenant & Akun Owner berhasil didaftarkan!');
    }

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,suspended,inactive'],
        ]);

        $tenant->update([
            'name'   => $validated['name'],
            'status' => $validated['status'],
        ]);

        return redirect('/admin/tenants')->with('success', 'Data Tenant berhasil diperbarui!');
    }

    public function destroy(Tenant $tenant)
    {
        DB::transaction(function () use ($tenant) {
            // Hapus seluruh user yang terikat pada tenant ini
            User::where('tenant_id', $tenant->id)->delete();
            
            // Hapus tenant
            $tenant->delete();
        });

        return redirect('/admin/tenants')->with('success', 'Tenant beserta seluruh penggunanya berhasil dihapus.');
    }

    public function show(Tenant $tenant)
    {
        // Load relasi users dan roles
        $tenant->load('users.roles');

        // Ambil owner (role tenant_superadmin) atau fallback ke user pertama
        $owner = $tenant->users->first(function ($user) {
            return $user->hasRole('tenant_superadmin');
        }) ?? $tenant->users->first();

        // Pengecekan aman untuk model yang mungkin belum dibuat
        $hasPondModel    = class_exists(\App\Models\Pond::class);
        $hasBatchModel   = class_exists(\App\Models\Batch::class);
        $hasHarvestModel = class_exists(\App\Models\Harvest::class);

        // Ringkasan Statistik Tanpa Filter Status
        $summary = [
            'total_users'        => $tenant->users->count(),
            'total_ponds'        => $hasPondModel ? \App\Models\Pond::where('tenant_id', $tenant->id)->count() : 0,
            'active_batches'     => $hasBatchModel ? \App\Models\Batch::where('tenant_id', $tenant->id)->count() : 0,
            'completed_batches'  => 0,
            'total_harvest_kg'   => $hasHarvestModel ? (\App\Models\Harvest::where('tenant_id', $tenant->id)->sum('total_weight_kg') ?? 0) : 0,
        ];

        // Sample Data Preview
        $pondsPreview         = $hasPondModel ? \App\Models\Pond::where('tenant_id', $tenant->id)->latest()->take(5)->get() : collect();
        $activeBatchesPreview = $hasBatchModel ? \App\Models\Batch::where('tenant_id', $tenant->id)->latest()->take(5)->get() : collect();

        return view('admin.tenants.show', compact('tenant', 'owner', 'summary', 'pondsPreview', 'activeBatchesPreview'));
    }

    // Halaman List Kolam milik Tenant spesifik
    public function ponds(Tenant $tenant)
    {
        $hasPondModel = class_exists(\App\Models\Pond::class);
        $ponds = $hasPondModel ? \App\Models\Pond::where('tenant_id', $tenant->id)->paginate(15) : collect();

        return view('admin.tenants.ponds', compact('tenant', 'ponds'));
    }

    // Halaman List Siklus/Batch milik Tenant spesifik
    public function batches(Tenant $tenant)
    {
        $hasBatchModel = class_exists(\App\Models\Batch::class);
        $batches = $hasBatchModel ? \App\Models\Batch::where('tenant_id', $tenant->id)->paginate(15) : collect();

        return view('admin.tenants.batches', compact('tenant', 'batches'));
    }

    // Halaman List Pengguna/Tim milik Tenant spesifik
    public function users(Tenant $tenant)
    {
        $users = $tenant->users()->paginate(15);

        return view('admin.tenants.users', compact('tenant', 'users'));
    }
    
    public function harvests(Tenant $tenant)
    {
        $hasHarvestModel = class_exists(\App\Models\HarvestLog::class);
        $harvests = $hasHarvestModel ? \App\Models\HarvestLog::where('tenant_id', $tenant->id)->paginate(15) : collect();

        return view('admin.tenants.harvests', compact('tenant', 'harvests'));
    }
}