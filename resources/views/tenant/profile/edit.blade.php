<x-layouts.app>
    <x-slot:title>Pengaturan Profil Usaha</x-slot:title>

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2>Profil Usaha Budidaya</h2>
        <p style="color: #6b7280; margin-bottom: 1.5rem;">Lengkapi informasi identitas usaha atau tambak Anda.</p>

        @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        <form action="/tenant/profile" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Nama Usaha / Tambak *</label>
                <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required placeholder="Contoh: Tambak Sukses Makmur" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tipe Usaha *</label>
                <select name="tenant_type" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="individual" {{ old('tenant_type', $tenant->tenant_type) == 'individual' ? 'selected' : '' }}>Perorangan / Pembudidaya Mandiri</option>
                    <option value="company" {{ old('tenant_type', $tenant->tenant_type) == 'company' ? 'selected' : '' }}>Perusahaan / PT / CV / Koperasi</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Kontak Utama / WA Usaha *</label>
                <input type="text" name="phone_or_email" value="{{ old('phone_or_email', $tenant->phone_or_email) }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                Simpan Profil Usaha
            </button>
        </form>
    </div>
</x-layouts.app>