<x-layouts.app>
    <x-slot:title>Log Kualitas Air</x-slot:title>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Catatan Kualitas Air Kolam</h2>
        <a href="/tenant/logs/water/create" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: bold;">+ Catat Kualitas Air</a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #f3f4f6; text-align: left; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 12px;">Tgl & Sesi</th>
                <th style="padding: 12px;">Kolam / Siklus</th>
                <th style="padding: 12px;">pH Air</th>
                <th style="padding: 12px;">DO (Oksigen)</th>
                <th style="padding: 12px;">Suhu (°C)</th>
                <th style="padding: 12px;">Warna & Kecerahan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($waterLogs as $log)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">
                        <strong>{{ \Carbon\Carbon::parse($log->check_date)->format('d M Y') }}</strong><br>
                        <small style="color: #6b7280; text-transform: capitalize;">Sesi {{ $log->check_time_session }}</small>
                    </td>
                    <td style="padding: 12px;">
                        <strong>{{ $log->batch->pond->name ?? '-' }}</strong><br>
                        <small style="color: #2563eb;">{{ $log->batch->batch_code ?? '-' }}</small>
                    </td>
                    <td style="padding: 12px; font-weight: bold;">{{ $log->ph ? number_format($log->ph, 1, ',', '.') : '-' }}</td>
                    <td style="padding: 12px;">{{ $log->do_mg_l ? number_format($log->do_mg_l, 1, ',', '.') . ' mg/L' : '-' }}</td>
                    <td style="padding: 12px;">{{ $log->temperature_c ? number_format($log->temperature_c, 1, ',', '.') . ' °C' : '-' }}</td>
                    <td style="padding: 12px;">
                        <strong>{{ $log->water_color ?? '-' }}</strong>
                        @if($log->transparency_cm)
                            <br><small style="color: #6b7280;">Kecerahan: {{ number_format($log->transparency_cm, 1, ',', '.') }} cm</small>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada catatan kualitas air.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.app>