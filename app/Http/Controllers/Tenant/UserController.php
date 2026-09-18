<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\EmployeeRequest; // Single Form Request
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;

        $users = User::where('tenant_id', $tenantId)
            ->where('id', '!=', auth()->id())
            ->with('roles')
            ->latest()
            ->paginate(10);

        return view('tenant.employees.index', compact('users'));
    }

    public function store(EmployeeRequest $request)
    {
        $user = User::create([
            'tenant_id' => auth()->user()->tenant_id,
            'name'      => $request->name,
            'email'     => $request->email,
            'username'  => $request->username,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'Akun staf baru berhasil ditambahkan!');
    }

    public function update(EmployeeRequest $request, User $user)
    {
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $data = $request->only(['name', 'email', 'username', 'phone']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        return back()->with('success', 'Data staf berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $user->delete();

        return back()->with('success', 'Akun staf berhasil dihapus!');
    }
}