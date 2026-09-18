<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Import Hash agar tidak error

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $request->input('login');

        // 1. Deteksi jenis input (Email, No HP, atau Username)
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) 
            ? 'email' 
            : (is_numeric($loginInput) ? 'phone' : 'username');

        // 2. Cari pengguna
        $user = User::where($fieldType, $loginInput)->first();

        // Error 1: Akun tidak ditemukan
        if (! $user) {
            $message = 'Akun tidak ditemukan. Silakan lakukan pendaftaran terlebih dahulu.';
            
            return $request->wantsJson()
                ? response()->json(['message' => $message], 404)
                : back()->withErrors(['login' => $message])->withInput();
        }

        // Error 2: Password salah
        if (! Hash::check($request->password, $user->password)) {
            $message = 'Password yang Anda masukkan salah.';

            return $request->wantsJson()
                ? response()->json(['message' => $message], 422)
                : back()->withErrors(['login' => $message])->withInput();
        }

        // 3. Cek Status Tenant sebelum mengizinkan login
        if ($user->tenant && ! $user->tenant->is_active) {
            $message = match ($user->tenant->status) {
                'diblokir'      => 'Akses ditolak. Akun perusahaan Anda telah diblokir.',
                'masa tenggang' => 'Masa uji coba/langganan Anda telah habis.',
                default         => 'Akses ditolak. Perusahaan Anda sedang tidak aktif.'
            };

            return $request->wantsJson()
                ? response()->json(['message' => $message], 403)
                : back()->withErrors(['login' => $message])->withInput();
        }

        // 4. Jika semua aman, buat session login
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // 5. Target Redirect berdasarkan Role Spatie
        $redirectTarget = $user->hasRole('superadmin') ? '/admin/dashboard' : '/tenant/dashboard';

        if ($request->wantsJson()) {
            return response()->json([
                'message'         => 'Login berhasil!',
                'redirect_target' => $redirectTarget,
                'user'            => $user->load('roles'),
            ]);
        }

        return redirect()->intended($redirectTarget);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Berhasil logout.']);
        }

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }
}