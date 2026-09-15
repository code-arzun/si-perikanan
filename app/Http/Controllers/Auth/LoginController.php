<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan Form Login (GET /login)
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses Login (POST /login)
    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $request->input('login');

        // Deteksi apakah input berupa Email, No HP, atau Username
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) 
            ? 'email' 
            : (is_numeric($loginInput) ? 'phone' : 'username');

        $credentials = [
            $fieldType => $loginInput,
            'password'  => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // 1. Cek jika akun User dinonaktifkan
            if (isset($user->is_active) && ! $user->is_active) {
                Auth::logout();
                $message = 'Akun Anda telah dinonaktifkan.';
                return $request->wantsJson()
                    ? response()->json(['message' => $message], 403)
                    : back()->withErrors(['login' => $message]);
            }

            // 2. Cek jika Tenant/Tambak tempat user bernaung sedang Suspended/Inactive (Non-Superadmin)
            if (! $user->is_superadmin && $user->tenant && $user->tenant->status !== 'active') {
                Auth::logout();
                $message = 'Akses ditolak. Perusahaan tambak Anda sedang ditangguhkan (Suspended/Inactive).';
                return $request->wantsJson()
                    ? response()->json(['message' => $message], 403)
                    : back()->withErrors(['login' => $message]);
            }

            // Target Redirect berdasarkan Peran
            $redirectTarget = $user->is_superadmin ? '/admin/dashboard' : '/tenant/dashboard';

            if ($request->wantsJson()) {
                return response()->json([
                    'message'         => 'Login berhasil!',
                    'redirect_target' => $redirectTarget,
                    'user'            => $user->load('roles'),
                ]);
            }

            return redirect()->intended($redirectTarget);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Username/No HP/Email atau password salah.'], 422);
        }

        return back()->withErrors(['login' => 'Username/No HP/Email atau password salah.'])->withInput();
    }

    // Memproses Logout (POST /logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Berhasil logout.']);
        }

        return redirect('/login')->with('success', 'Anda telah berhasil keluar.');
    }
}