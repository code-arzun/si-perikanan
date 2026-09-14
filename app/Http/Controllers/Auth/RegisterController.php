<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = DB::transaction(function () use ($request) {
            // 1. Buat record Tenant baru (Nama usaha nullable, diisi nanti)
            $tenant = Tenant::create([
                'name'           => null,
                'phone_or_email' => $request->phone,
                'status'         => 'trial',
                'tenant_type'    => 'individual',
            ]);

            // 2. Buat User utama pendaftar
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name'      => $request->name,
                'username'  => strtolower($request->username),
                'phone'     => $request->phone,
                'email'     => $request->email ?? null,
                'password'  => Hash::make($request->password),
                'is_active' => true,
            ]);

            // 3. Assign Role tenant_superadmin
            $user->assignRole('tenant_superadmin');

            return $user;
        });

        // 4. Otomatis Login setelah registrasi
        Auth::login($user);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Registrasi berhasil!',
                'user'    => $user->load('roles'),
            ], 201);
        }

        return redirect('/tenant/dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }
}