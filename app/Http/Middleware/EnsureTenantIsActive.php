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

        // 1. Lewatkan jika pengguna adalah Superadmin Provider
        if ($user && $user->hasRole('superadmin')) {
            return $next($request);
        }

        // 2. Cek apakah user memiliki relasi tenant
        if ($user && $user->tenant) {
            
            // PERBAIKAN: Jika tenant TIDAK aktif (bukan 'aktif' atau 'uji coba')
            if (! $user->tenant->is_active) {
                
                // Logout paksa user dari sesi
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = match ($user->tenant->status) {
                    'masa tenggang' => 'Akses ditolak. Akun Anda sedang dalam masa tenggang (Grace Period).',
                    'diblokir'      => 'Akses ditolak. Akun Anda telah diblokir.',
                    default         => 'Akses ditolak. Akun Anda tidak aktif.',
                };

                return redirect('/')->withErrors([
                    'login' => $message
                ]);
            }
        }

        return $next($request);
    }
}