<x-layouts.app>
    <x-slot:title>Superadmin - Master Roles & Permissions</x-slot:title>

    <div style="margin-bottom: 1.5rem;">
        <h2 style="color: #1e3a8a; margin: 0;">Master Roles & Permissions Global</h2>
        <span style="color: #6b7280; font-size: 0.9rem;">Kelola fitur-fitur platform (Permissions), preset role standar, serta relasi hak aksesnya.</span>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; align-items: start;">
        
        {{-- KOLOM KIRI: Form Tambah Permission & Role --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            {{-- Form Tambah Permission --}}
            <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="margin-top: 0; color: #1e293b; font-size: 1rem;">+ Master Permission Baru</h3>
                <form action="/admin/permissions" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-weight: bold; margin-bottom: 4px; font-size: 0.8rem;">Nama Permission (modul.aksi)</label>
                        <input type="text" name="name" required placeholder="Contoh: iot.view" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                        <small style="color: #64748b; font-size: 0.75rem;">Gunakan format titik, contoh: <code>pond.create</code></small>
                    </div>
                    <button type="submit" style="background: #0284c7; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%;">Simpan Permission</button>
                </form>
            </div>

            {{-- Form Tambah Role Baru --}}
            <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="margin-top: 0; color: #1e293b; font-size: 1rem;">+ Preset Role Global Baru</h3>
                <form action="/admin/roles" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-weight: bold; margin-bottom: 4px; font-size: 0.8rem;">Nama Role</label>
                        <input type="text" name="name" required placeholder="Contoh: Teknisi Air" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                    </div>
                    <button type="submit" style="background: #2563eb; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%;">Simpan Role Baru</button>
                </form>
            </div>

            {{-- Daftar Master Permissions (Edit & Delete Inline) --}}
            <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="margin-top: 0; color: #1e293b; font-size: 1rem; margin-bottom: 0.75rem;">Daftar Master Permissions</h3>
                <div style="max-height: 400px; overflow-y: auto;">
                    @foreach($permissions as $group => $perms)
                        <div style="margin-bottom: 0.75rem;">
                            <strong style="color: #64748b; text-transform: uppercase; font-size: 0.75rem; display: block; margin-bottom: 4px;">Modul: {{ $group }}</strong>
                            @foreach($perms as $perm)
                                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 4px 8px; border-radius: 4px; margin-bottom: 4px; font-size: 0.8rem;">
                                    <span>{{ $perm->name }}</span>
                                    <form action="/admin/permissions/{{ $perm->id }}" method="POST" onsubmit="return confirm('Hapus permission ini?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: transparent; color: #ef4444; border: none; cursor: pointer; font-size: 0.75rem; font-weight: bold;">[Hapus]</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN: Matriks Role Has Permission (Edit Role & Checkbox Matrix) --}}
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; color: #1e293b; margin-bottom: 1rem;">Matriks Role & Hak Akses (Role Has Permission)</h3>
            
            @foreach($roles as $role)
                <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; background: #fafafa;">
                    
                    {{-- Header Row: Form Rename Role & Delete Role --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0;">
                        <form action="/admin/roles/{{ $role->id }}" method="POST" style="display: flex; align-items: center; gap: 8px; margin: 0;">
                            @csrf
                            @method('PUT')
                            <strong style="color: #1e293b;">Role:</strong>
                            <input type="text" name="name" value="{{ $role->name }}" required style="padding: 4px 8px; border: 1px solid #ccc; border-radius: 4px; font-weight: bold; color: #1e3a8a;">
                            <button type="submit" style="background: #e2e8f0; color: #334155; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; cursor: pointer;">Rename</button>
                        </form>

                        @if(!in_array(strtolower($role->name), ['owner', 'manager', 'operator']))
                            <form action="/admin/roles/{{ $role->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role {{ $role->name }}?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; cursor: pointer;">Hapus Role</button>
                            </form>
                        @else
                            <span style="font-size: 0.75rem; color: #64748b; font-style: italic;">(Role Bawaan Sistem)</span>
                        @endif
                    </div>

                    {{-- Form Matriks Checkbox Permissions --}}
                    <form action="/admin/roles/{{ $role->id }}/permissions" method="POST">
                        @csrf
                        @method('PUT')

                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; margin-bottom: 1rem;">
                            @foreach($permissions as $group => $perms)
                                <div style="background: white; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                    <strong style="color: #2563eb; text-transform: uppercase; font-size: 0.75rem; display: block; margin-bottom: 6px;">{{ $group }}</strong>
                                    @foreach($perms as $perm)
                                        <label style="display: block; cursor: pointer; color: #334155; font-size: 0.85rem; margin-bottom: 4px;">
                                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                                            {{ $perm->name }}
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div style="text-align: right;">
                            <button type="submit" style="background: #16a34a; color: white; border: none; padding: 6px 16px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; cursor: pointer;">Simpan Hak Akses {{ $role->name }}</button>
                        </div>
                    </form>

                </div>
            @endforeach
        </div>

    </div>
</x-layouts.app>