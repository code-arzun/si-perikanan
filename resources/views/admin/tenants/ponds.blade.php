<x-layouts.app>
    <x-slot:title>Daftar Kolam - {{ $tenant->name }}</x-slot:title>

    <x-admin.header 
        title="Daftar Kolam Tenant: {{ $tenant->name }}" 
        subtitle="Daftar seluruh fasilitas kolam budidaya yang terdaftar pada tenant ini.">
        <x-slot:action>
            <x-admin.button href="{{ route('admin.tenants.show', $tenant->id) }}" variant="secondary">
                ← Kembali ke Detail Tenant
            </x-admin.button>
        </x-slot:action>
    </x-admin.header>

    <div style="background: white; border-radius: 8px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                    <th style="padding: 12px;">#</th>
                    <th style="padding: 12px;">Nama Kolam</th>
                    <th style="padding: 12px;">Tipe Konstruksi</th>
                    <th style="padding: 12px;">Luas / Volume</th>
                    <th style="padding: 12px;">Tanggal Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ponds as $index => $pond)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px; color: #64748b;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; font-weight: bold; color: #1e293b;">{{ $pond->name }}</td>
                        <td style="padding: 12px; text-transform: capitalize;">{{ $pond->type ?? 'Beton/Terpal' }}</td>
                        <td style="padding: 12px; color: #334155;">{{ $pond->area_m2 ?? '-' }} m²</td>
                        <td style="padding: 12px; color: #64748b;">{{ $pond->created_at ? $pond->created_at->format('d M Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #94a3b8;">
                            Belum ada data kolam yang terdaftar untuk tenant ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(is_object($ponds) && method_exists($ponds, 'links'))
            <div style="margin-top: 1rem;">
                {{ $ponds->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>