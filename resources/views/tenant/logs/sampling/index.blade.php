<x-layouts.app>
    <x-slot:title>Log Sampling Pertumbuhan</x-slot:title>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Catatan Sampling Pertumbuhan (MBW)</h2>
        <a href="/tenant/logs/sampling/create" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: bold;">+ Catat Sampling Baru</a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #f3f4f6; text-align: left; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 12px;">Tanggal</th>
                <th style="padding: 12px;">Kolam / Siklus</th>
                <th style="padding: 12px;">Jumlah Sample</th>
                <th style="padding: 12px;">Bobot Rata-rata (MBW)</th>
                <th style="padding: 12px;">Panjang Rata-rata</th>
                <th style="padding: 12px;">Estimasi Biomasa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($samplingLogs as $log)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;"><strong>{{ \Carbon\Carbon::parse($log->sampling_date)->format('d M Y') }}</strong></td>
                    <td style="padding: 12px;">
                        <strong>{{ $log->batch->pond->name ?? '-' }}</strong><br>
                        <small style="color: #2563eb;">{{ $log->batch->batch_code ?? '-' }}</small>
                    </td>
                    <td style="padding: 12px;">{{ number_format($log->sample_count_pcs) }} ekor</td>
                    <td style="padding: 12px; color: #2563eb; font-weight: bold;">{{ number_format($log->avg_weight_g, 2, ',', '.') }} Gram</td>
                    <td style="padding: 12px;">{{ $log->avg_length_cm ? number_format($log->avg_length_cm, 1, ',', '.') . ' cm' : '-' }}</td>
                    <td style="padding: 12px; font-weight: bold;">{{ $log->estimated_biomass_kg ? number_format($log->estimated_biomass_kg, 2, ',', '.') . ' Kg' : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada catatan sampling pertumbuhan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.app>