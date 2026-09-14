<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\TenantProfileRequest;
use Illuminate\Http\Request;

class TenantProfileController extends Controller
{
    public function edit()
    {
        $tenant = auth()->user()->tenant;

        return view('tenant.profile.edit', compact('tenant'));
    }

    public function update(TenantProfileRequest $request)
    {
        $tenant = auth()->user()->tenant;
        $tenant->update($request->validated());

        return back()->with('success', 'Profil usaha berhasil diperbarui!');
    }
}