<x-layouts.guest>
    <x-slot:title>Pendaftaran Akun Baru</x-slot:title>

    <div style="max-width: 450px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="margin-top: 0;">Daftar Akun Budidaya</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/register" method="POST">
            @csrf
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Nama Pemilik / Pengguna *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Pak Budi" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Username *</label>
                <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: budifarm" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Nomor HP / WhatsApp *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="Contoh: 081234567890" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Email (Opsional)</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="budi@gmail.com" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Password *</label>
                <input type="password" name="password" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" style="width: 100%; background: #2563eb; color: white; padding: 10px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                Daftar Sekarang
            </button>
        </form>
    </div>
</x-layouts.guest>