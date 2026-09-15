<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MasterRolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function($item) {
            return explode('.', $item->name)[0]; // Grouping per modul (pond, batch, log, harvest, team)
        });

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    // --- PERMISSION CRUD ---

    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create([
            'name'       => strtolower(trim($request->name)),
            'guard_name' => 'web'
        ]);

        return back()->with('success', 'Permission Master baru berhasil ditambahkan!');
    }

    public function updatePermission(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => strtolower(trim($request->name))]);

        return back()->with('success', 'Permission berhasil diperbarui!');
    }

    public function destroyPermission(Permission $permission)
    {
        $permission->delete();
        return back()->with('success', 'Permission berhasil dihapus.');
    }

    // --- ROLE CRUD ---

    public function storeRole(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name'       => trim($request->name),
            'guard_name' => 'web'
        ]);

        if (!empty($request->permissions)) {
            $role->syncPermissions($request->permissions);
        }

        return back()->with('success', 'Preset Role Global berhasil dibuat!');
    }

    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => trim($request->name)]);

        return back()->with('success', 'Nama Role berhasil diperbarui!');
    }

    public function destroyRole(Role $role)
    {
        // Proteksi agar Role bawaan sistem tidak sengaja terhapus
        if (in_array(strtolower($role->name), ['owner', 'manager', 'operator'])) {
            return back()->with('error', 'Role bawaan sistem (Owner, Manager, Operator) tidak boleh dihapus!');
        }

        $role->delete();
        return back()->with('success', 'Role berhasil dihapus.');
    }

    // --- ROLE HAS PERMISSION (RELATION MATRIX) ---

    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Matriks Hak Akses Role berhasil diperbarui!');
    }
}