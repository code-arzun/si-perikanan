<x-layouts.app>
    <x-slot:title>Log Kematian Ikan</x-slot:title>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Catatan Kematian Ikan (Mortality)</h2>
        <a href="/tenant/logs/mortality/create" style="background: #ef4444; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: bold;">+ Catat Kematian</a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #f3f4f6; text-align: left; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 12px;">Tanggal Log</th>
                <th style="padding: 12px;">Kolam / Siklus</th>
                <th style="padding: 12px;">Jumlah Mati</th>
                <th style="padding: 12px;">Total Bobot</th>
                <th style="padding: 12px;">Dugaan Penyebab & Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mortalityLogs as $log)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;"><strong>{{ \Carbon\Carbon::parse($log->log_date)->format('d M Y') }}</strong></td>
                    <td style="padding: 12px;">
                        <strong>{{ $log->batch->pond->name ?? '-' }}</strong><br>
                        <small style="color: #2563eb;">{{ $log->batch->batch_code ?? '-' }}</small>
                    </td>
                    <td style="padding: 12px; color: #dc2626;"><strong>{{ number_format($log->quantity_pcs) }} ekor</strong></td>
                    <td style="padding: 12px;">{{ $log->total_weight_kg ? number_format($log->total_weight_kg, 2, ',', '.') . ' Kg' : '-' }}</td>
                    <td style="padding: 12px;">
                        <strong>{{ $log->indication ?? 'Tidak Diketahui' }}</strong><br>
                        <small style="color: #6b7280;">{{ $log->action_taken ?? '-' }}</small>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada catatan kematian ikan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $mortalityLogs->links() }}
    </div>
</x-layouts.app>