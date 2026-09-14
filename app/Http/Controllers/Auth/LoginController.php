<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'], // Bisa Username, No HP, atau Email
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

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();
                return back()->withErrors(['login' => 'Akun Anda telah dinonaktifkan.']);
            }

            $redirectTarget = $user->hasRole('saas_admin') ? '/saas/dashboard' : '/tenant/dashboard';

            if ($request->wantsJson()) {
                return response()->json([
                    'message'         => 'Login berhasil!',
                    'redirect_target' => $redirectTarget,
                    'user'            => $user->load('roles'),
                ]);
            }

            return redirect($redirectTarget);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Username/No HP/Email atau password salah.'], 422);
        }

        return back()->withErrors(['login' => 'Username/No HP/Email atau password salah.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Berhasil logout.']);
        }

        return redirect('/login');
    }
}