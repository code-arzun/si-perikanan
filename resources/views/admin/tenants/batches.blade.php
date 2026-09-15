<x-layouts.app>
    <x-slot:title>Siklus Budidaya - {{ $tenant->name }}</x-slot:title>

    <x-admin.header 
        title="Siklus Budidaya Tenant: {{ $tenant->name }}" 
        subtitle="Histori dan daftar siklus budidaya yang berjalan maupun yang telah selesai.">
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
                    <th style="padding: 12px;">Kode Batch</th>
                    <th style="padding: 12px;">Kolam</th>
                    <th style="padding: 12px;">Tanggal Tebar</th>
                    <th style="padding: 12px;">Jumlah Tebar</th>
                    <th style="padding: 12px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batches as $batch)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px; font-weight: bold; color: #2563eb;">{{ $batch->batch_code ?? 'BATCH-' . $batch->id }}</td>
                        <td style="padding: 12px; color: #1e293b;">{{ $batch->pond->name ?? '-' }}</td>
                        <td style="padding: 12px; color: #64748b;">{{ $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->format('d M Y') : '-' }}</td>
                        <td style="padding: 12px; font-weight: 500;">{{ number_format($batch->initial_seed_count ?? 0, 0, ',', '.') }} ekor</td>
                        <td style="padding: 12px;">
                            <span style="padding: 3px 8px; border-radius: 12px; font-size: 0.78rem; font-weight: bold; background: #dcfce7; color: #15803d;">
                                {{ ucfirst($batch->status ?? 'Aktif') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #94a3b8;">
                            Belum ada siklus budidaya yang tercatat untuk tenant ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(is_object($batches) && method_exists($batches, 'links'))
            <div style="margin-top: 1rem;">
                {{ $batches->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>