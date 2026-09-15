<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Lewatkan jika pengguna adalah Superadmin
        if ($user && $user->is_superadmin) {
            return $next($request);
        }

        // 2. Cek apakah user memiliki relasi tenant
        if ($user && $user->tenant) {
            
            // Jika status tenant bukan 'active' (misal: 'suspended' atau 'inactive')
            if ($user->tenant->status !== 'active') {
                
                // Logout paksa user dari sesi
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')->withErrors([
                    'login' => 'Akses ditolak. Akun atau perusahaan tambak Anda sedang ditangguhkan (Suspended/Inactive). Silakan hubungi Customer Support.'
                ]);
            }
        }

        return $next($request);
    }
}