<x-layouts.app>
    <x-slot:title>Superadmin - Tambah Tenant Baru</x-slot:title>

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="color: #1e3a8a; margin-top: 0;">Pendaftaran Tenant Manual (Enterprise)</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/admin/tenants" method="POST">
            @csrf

            <h4 style="margin-bottom: 0.5rem; color: #3b82f6;">Akun Pemilik (Owner Utama)</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Username *</label>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: hendras" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Nama Lengkap Owner *</label>
                    <input type="text" name="owner_name" value="{{ old('owner_name') }}" required placeholder="Contoh: Hendra Swastika" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Nomor Telepon / WA *</label>
                    <input type="text" name="owner_phone" value="{{ old('owner_phone') }}" required placeholder="08123456789" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Email <small style="color: #6b7280; font-weight: normal;">(Opsional)</small></label>
                    <input type="email" name="owner_email" value="{{ old('owner_email') }}" placeholder="hendra@tambak.com" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Password Login *</label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1.5rem 0;">

            <h4 style="margin-bottom: 0.5rem; color: #3b82f6;">Informasi Tambak & Tipe Tenant</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tipe Tenant *</label>
                    <select name="tenant_type" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="individual" {{ old('tenant_type') == 'individual' ? 'selected' : '' }}>Perseorangan (Individual)</option>
                        <option value="corporate" {{ old('tenant_type') == 'corporate' ? 'selected' : '' }}>Perusahaan (Corporate)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Nama Perusahaan / Tambak <small style="color: #6b7280; font-weight: normal;">(Opsional)</small></label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Contoh: PT Tambak Udang Nusantara" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Tenant Baru</button>
                <a href="/admin/tenants" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>