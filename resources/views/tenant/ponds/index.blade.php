<x-layouts.app>
    <x-slot:title>Data Kolam</x-slot:title>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Daftar Kolam Budidaya</h2>
        <a href="/tenant/ponds/create" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: bold;">+ Tambah Kolam</a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #f3f4f6; text-align: left; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 12px;">Kode & Nama</th>
                <th style="padding: 12px;">Bentuk & Dimensi</th>
                <th style="padding: 12px;">Volume Air</th>
                <th style="padding: 12px;">Status</th>
                <th style="padding: 12px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ponds as $pond)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">
                        <strong>{{ $pond->code }}</strong><br>
                        <small style="color: #6b7280;">{{ $pond->name }}</small>
                    </td>
                    <td style="padding: 12px;">
                        <strong style="text-transform: capitalize;">{{ $pond->shape }}</strong>
                        @if($pond->shape === 'bulat')
                            <small style="display: block; color: #6b7280;">Ø {{ number_format($pond->diameter, 2, ',', '.') }}m | T: {{ number_format($pond->depth, 2, ',', '.') }}m</small>
                        @elseif(in_array($pond->shape, ['persegi', 'persegi panjang']))
                            <small style="display: block; color: #6b7280;">{{ number_format($pond->length, 2, ',', '.') }}m x {{ number_format($pond->width, 2, ',', '.') }}m | T: {{ number_format($pond->depth, 2, ',', '.') }}m</small>
                        @endif
                    </td>
                    <td style="padding: 12px;"><strong>{{ number_format($pond->volume_m3, 2, ',', '.') }} m³</strong></td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background: {{ $pond->status === 'aktif' ? '#dcfce7' : '#f3f4f6' }}; color: {{ $pond->status === 'aktif' ? '#15803d' : '#4b5563' }}; text-transform: uppercase;">
                            {{ $pond->status }}
                        </span>
                    </td>
                    <td style="padding: 12px;">
                        <form action="/tenant/ponds/{{ $pond->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kolam ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #ef4444; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 12px;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada data kolam.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.app>