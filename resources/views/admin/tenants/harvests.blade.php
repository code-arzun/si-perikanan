<x-layouts.app>
    <x-slot:title>Histori Panen - {{ $tenant->name }}</x-slot:title>

    <x-admin.header 
        title="Histori Panen Tenant: {{ $tenant->name }}" 
        subtitle="Rekapitulasi dan riwayat seluruh hasil panen budidaya yang telah dicatat.">
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
                    <th style="padding: 12px;">Tanggal Panen</th>
                    <th style="padding: 12px;">Kode Batch / Siklus</th>
                    <th style="padding: 12px;">Kolam</th>
                    <th style="padding: 12px;">Total Berat (kg)</th>
                    <th style="padding: 12px;">Pemasukan</th>
                    <th style="padding: 12px;">Tipe Panen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($harvests as $index => $harvest)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px; color: #64748b;">{{ $index + 1 }}</td>
                        <td style="padding: 12px; font-weight: bold; color: #1e293b;">
                            {{ $harvest->harvest_date ? \Carbon\Carbon::parse($harvest->harvest_date)->format('d M Y') : '-' }}
                        </td>
                        <td style="padding: 12px; color: #2563eb; font-weight: 500;">
                            {{ $harvest->batch->batch_code ?? 'BATCH-' . ($harvest->batch_id ?? '-') }}
                        </td>
                        <td style="padding: 12px; color: #334155;">
                            {{ $harvest->pond->name ?? ($harvest->batch->pond->name ?? '-') }}
                        </td>
                        <td style="padding: 12px; font-weight: bold; color: #16a34a;">
                            {{ number_format($harvest->weight_kg ?? 0, 2, ',', '.') }} kg
                        </td>
                        <td style="padding: 12px; color: #64748b;">
                            {{ $harvest->total_revenue ? 'Rp ' . number_format($harvest->total_revenue, 0, ',', '.') : '-' }}
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 3px 8px; border-radius: 12px; font-size: 0.78rem; font-weight: bold; {{ ($harvest->type ?? 'total') === 'partial' ? 'background: #fef3c7; color: #b45309;' : 'background: #dcfce7; color: #15803d;' }}">
                                {{ ucfirst($harvest->type ?? 'Panen Total') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center; color: #94a3b8;">
                            Belum ada riwayat panen yang dicatat untuk tenant ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(is_object($harvests) && method_exists($harvests, 'links'))
            <div style="margin-top: 1rem;">
                {{ $harvests->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>