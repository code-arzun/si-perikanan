<x-layouts.app>
    <x-slot:title>Kelola Supplier & Buyer - Workspace Tenant</x-slot:title>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="margin: 0; color: #1e293b; font-size: 1.5rem;">🎴 Kontak Supplier & Buyer</h2>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.9rem;">
                Kelola data relasi bisnis (pemasok pakan/obat dan pembeli hasil panen) untuk pencatatan arus kas.
            </p>
        </div>
    </div>

    {{-- Pesan Sukses Notifikasi --}}
    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #15803d; padding: 12px 16px; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; align-items: start;">
        
        {{-- FORM INPUT KONTAK BARU --}}
        <div style="background: white; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; margin-bottom: 1rem; color: #0f172a; font-size: 1.05rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                ➕ Tambah Kontak Baru
            </h3>

            <form action="{{ route('tenant.contacts.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">
                        Nama Kontak / Instansi <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: UD Pakan Mandiri" required style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem;">
                    @error('name')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">
                        Tipe Kontak <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="type" required style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; background: white;">
                        <option value="supplier" {{ old('type') == 'supplier' ? 'selected' : '' }}>Supplier (Pemasok Pakan/Obat/Benih)</option>
                        <option value="buyer" {{ old('type') == 'buyer' ? 'selected' : '' }}>Buyer (Pembeli Hasil Panen)</option>
                        <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Kedua-duanya (Supplier & Buyer)</option>
                    </select>
                    @error('type')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">No. HP / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08123456789" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="kontak@mitra.com" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem;">
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">Alamat Singkat</label>
                    <textarea name="address" rows="2" placeholder="Jl. Raya Budidaya No. 12..." style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; resize: vertical;">{{ old('address') }}</textarea>
                </div>

                <button type="submit" style="width: 100%; background: #2563eb; color: white; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.9rem;">
                    Simpan Kontak
                </button>
            </form>
        </div>

        {{-- TABEL DAFTAR KONTAK --}}
        <div style="background: white; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            
            {{-- Filter Kategori Kontak --}}
            <div style="display: flex; gap: 8px; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <a href="{{ route('tenant.contacts.index') }}" style="padding: 6px 14px; border-radius: 20px; text-decoration: none; font-size: 0.85rem; font-weight: bold; {{ !request('type') ? 'background: #0f172a; color: white;' : 'background: #f1f5f9; color: #475569;' }}">
                    Semua Kontak
                </a>
                <a href="{{ route('tenant.contacts.index', ['type' => 'supplier']) }}" style="padding: 6px 14px; border-radius: 20px; text-decoration: none; font-size: 0.85rem; font-weight: bold; {{ request('type') == 'supplier' ? 'background: #2563eb; color: white;' : 'background: #f1f5f9; color: #475569;' }}">
                    📦 Supplier
                </a>
                <a href="{{ route('tenant.contacts.index', ['type' => 'buyer']) }}" style="padding: 6px 14px; border-radius: 20px; text-decoration: none; font-size: 0.85rem; font-weight: bold; {{ request('type') == 'buyer' ? 'background: #16a34a; color: white;' : 'background: #f1f5f9; color: #475569;' }}">
                    🛍️ Buyer / Pembeli
                </a>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                        <th style="padding: 10px;">Nama</th>
                        <th style="padding: 10px;">Kategori</th>
                        <th style="padding: 10px;">Kontak</th>
                        <th style="padding: 10px;">Alamat</th>
                        <th style="padding: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px; font-weight: bold; color: #1e293b;">
                                {{ $contact->name }}
                            </td>
                            <td style="padding: 10px;">
                                @if($contact->type === 'supplier')
                                    <span style="background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 12px; font-size: 0.78rem; font-weight: bold;">
                                        Supplier
                                    </span>
                                @elseif($contact->type === 'buyer')
                                    <span style="background: #dcfce7; color: #15803d; padding: 3px 8px; border-radius: 12px; font-size: 0.78rem; font-weight: bold;">
                                        Buyer
                                    </span>
                                @else
                                    <span style="background: #fef3c7; color: #b45309; padding: 3px 8px; border-radius: 12px; font-size: 0.78rem; font-weight: bold;">
                                        Supplier & Buyer
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 10px; color: #334155;">
                                <div>{{ $contact->phone ?? '-' }}</div>
                                <small style="color: #64748b;">{{ $contact->email }}</small>
                            </td>
                            <td style="padding: 10px; color: #64748b; font-size: 0.85rem;">
                                {{ $contact->address ?? '-' }}
                            </td>
                            <td style="padding: 10px; text-align: center;">
                                <form action="{{ route('tenant.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kontak ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Kontak" style="background: #ef4444; color: white; border: none; padding: 5px 8px; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 2rem; text-align: center; color: #94a3b8;">
                                Belum ada kontak relasi yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(is_object($contacts) && method_exists($contacts, 'links'))
                <div style="margin-top: 1rem;">
                    {{ $contacts->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.app>