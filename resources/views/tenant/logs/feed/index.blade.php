<x-layouts.app>
    <x-slot:title>Log Pakan Harian</x-slot:title>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Catatan Pemberian Pakan Harian</h2>
        <a href="/tenant/logs/feed/create" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: bold;">+ Catat Pakan Baru</a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #f3f4f6; text-align: left; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 12px;">Tanggal & Jam</th>
                <th style="padding: 12px;">Kolam / Siklus</th>
                <th style="padding: 12px;">Pakan & Jumlah</th>
                <th style="padding: 12px;">Respon Pakan</th>
                <th style="padding: 12px;">Dicatat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedLogs as $log)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">
                        <strong>{{ \Carbon\Carbon::parse($log->feed_date)->format('d M Y') }}</strong><br>
                        <small style="color: #6b7280;">{{ $log->feed_time }} WIB</small>
                    </td>
                    <td style="padding: 12px;">
                        <strong>{{ $log->batch->pond->name ?? '-' }}</strong><br>
                        <small style="color: #2563eb;">{{ $log->batch->batch_code ?? '-' }}</small>
                    </td>
                    <td style="padding: 12px;">
                        <strong>{{ number_format($log->amount_kg, 2, ',', '.') }} Kg</strong><br>
                        <small style="color: #6b7280;">{{ $log->feedType->name ?? 'Pakan Standar' }}</small>
                    </td>
                    <td style="padding: 12px; text-transform: capitalize;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background: {{ $log->appetite_response === 'sangat_baik' || $log->appetite_response === 'baik' ? '#dcfce7' : '#fee2e2' }}; color: {{ $log->appetite_response === 'sangat_baik' || $log->appetite_response === 'baik' ? '#15803d' : '#991b1b' }};">
                            {{ str_replace('_', ' ', $log->appetite_response) }}
                        </span>
                    </td>
                    <td style="padding: 12px;">{{ $log->user->name ?? 'Sistem' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada catatan pemberian pakan harian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $feedLogs->links() }}
    </div>
</x-layouts.app>