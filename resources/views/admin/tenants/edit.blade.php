<x-layouts.app>
    <x-slot:title>Superadmin - Edit Tenant</x-slot:title>

    <div style="max-width: 550px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="color: #1e3a8a; margin-top: 0;">Edit Data Tenant</h2>

        <form action="/admin/tenants/{{ $tenant->id }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Nama Perusahaan / Perusahaaan Tambak *</label>
                <input type="text" name="name" value="{{ old('name', $tenant->name) }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Status Keaktifan Tenant *</label>
                <select name="is_active" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="1" {{ $tenant->is_active ? 'selected' : '' }}>Aktif (Dapat Mengakses Workspace)</option>
                    <option value="0" {{ !$tenant->is_active ? 'selected' : '' }}>Nonaktif / Suspend (Akses Ditolak)</option>
                </select>
                <small style="color: #64748b; margin-top: 4px; display: block;">Jika dinonaktifkan, seluruh user yang terikat pada tenant ini tidak bisa login.</small>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Perubahan</button>
                <a href="/admin/tenants" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>