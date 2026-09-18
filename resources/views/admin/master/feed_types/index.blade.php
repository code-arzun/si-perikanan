<x-layouts.app>
    <x-slot:title>Master Jenis Pakan - Admin</x-slot:title>

    <x-admin.header 
        title="🌾 Master Jenis Pakan" 
        subtitle="Kelola referensi kategori/jenis pakan global (Pelet Apung, Pelet Tenggelam, Pakan Alami, Probiotik, dll).">
    </x-admin.header>

    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #15803d; padding: 12px 16px; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; align-items: start;">
        
        {{-- FORM TAMBAH --}}
        <div style="background: white; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; margin-bottom: 1rem; color: #0f172a; font-size: 1.05rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                ➕ Tambah Jenis Pakan
            </h3>

            <form action="{{ route('admin.feed-types.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">
                        Nama Jenis Pakan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Pelet Apung High Protein" required style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem;">
                    @error('name')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">Deskripsi / Keterangan</label>
                    <textarea name="description" rows="3" placeholder="Deskripsi jenis pakan..." style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; resize: vertical;">{{ old('description') }}</textarea>
                </div>

                <div style="margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_active" id="is_active_feed" value="1" checked style="cursor: pointer;">
                    <label for="is_active_feed" style="font-size: 0.85rem; color: #334155; cursor: pointer;">Status Aktif</label>
                </div>

                <button type="submit" style="width: 100%; background: #2563eb; color: white; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.9rem;">
                    Simpan Jenis Pakan
                </button>
            </form>
        </div>

        {{-- TABEL DATA --}}
        <div style="background: white; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                        <th style="padding: 10px;">#</th>
                        <th style="padding: 10px;">Jenis Pakan</th>
                        <th style="padding: 10px;">Deskripsi</th>
                        <th style="padding: 10px;">Status</th>
                        <th style="padding: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedTypes as $index => $feed)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px; color: #64748b;">{{ $index + 1 }}</td>
                            <td style="padding: 10px; font-weight: bold; color: #1e293b;">{{ $feed->name }}</td>
                            <td style="padding: 10px; color: #64748b; font-size: 0.85rem;">{{ $feed->description ?? '-' }}</td>
                            <td style="padding: 10px;">
                                @if($feed->is_active)
                                    <span style="background: #dcfce7; color: #15803d; padding: 3px 8px; border-radius: 12px; font-size: 0.78rem; font-weight: bold;">Aktif</span>
                                @else
                                    <span style="background: #f1f5f9; color: #64748b; padding: 3px 8px; border-radius: 12px; font-size: 0.78rem; font-weight: bold;">Nonaktif</span>
                                @endif
                            </td>
                            <td style="padding: 10px; text-align: center;">
                                <form action="{{ route('admin.feed-types.destroy', $feed->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jenis pakan ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Jenis Pakan" style="background: #ef4444; color: white; border: none; padding: 5px 8px; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 2rem; text-align: center; color: #94a3b8;">
                                Belum ada data jenis pakan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(is_object($feedTypes) && method_exists($feedTypes, 'links'))
                <div style="margin-top: 1rem;">
                    {{ $feedTypes->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>