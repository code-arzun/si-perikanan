<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    protected function authenticated(Request $request, $user)
{
    if ($user->hasRole('superadmin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('tenant_superadmin')) {
        return redirect()->route('tenant.dashboard');
    }

    if ($user->hasRole('tenant_admin_keuangan')) {
        return redirect()->route('tenant.finance.index');
    }

    if ($user->hasRole('tenant_staf_kolam')) {
        return redirect()->route('tenant.ponds.index'); // Atau halaman operasional kolam
    }

    return redirect('/');
}
}
