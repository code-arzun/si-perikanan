<x-layouts.app>
    <x-slot:title>Log Panen & Penjualan</x-slot:title>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Catatan Panen & Hasil Penjualan</h2>
        <a href="/tenant/harvests/create" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: bold;">+ Catat Panen Baru</a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #f3f4f6; text-align: left; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 12px;">Tanggal Panen</th>
                <th style="padding: 12px;">Kolam / Siklus</th>
                <th style="padding: 12px;">Tipe Panen</th>
                <th style="padding: 12px;">Hasil Panen (Kg)</th>
                <th style="padding: 12px;">Harga / Kg</th>
                <th style="padding: 12px;">Total Pendapatan</th>
                <th style="padding: 12px;">Pembeli</th>
            </tr>
        </thead>
        <tbody>
            @forelse($harvestLogs as $log)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;"><strong>{{ \Carbon\Carbon::parse($log->harvest_date)->format('d M Y') }}</strong></td>
                    <td style="padding: 12px;">
                        <strong>{{ $log->batch->pond->name ?? '-' }}</strong><br>
                        <small style="color: #2563eb;">{{ $log->batch->batch_code ?? '-' }}</small>
                    </td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; background: {{ $log->harvest_type === 'total' ? '#fee2e2' : '#e0e7ff' }}; color: {{ $log->harvest_type === 'total' ? '#991b1b' : '#3730a3' }}; text-transform: uppercase;">
                            {{ $log->harvest_type === 'total' ? 'Panen Total' : 'Parsial' }}
                        </span>
                    </td>
                    <td style="padding: 12px; font-weight: bold; color: #16a34a;">{{ number_format($log->weight_kg, 2, ',', '.') }} Kg</td>
                    <td style="padding: 12px;">{{ $log->price_per_kg ? 'Rp ' . number_format($log->price_per_kg, 0, ',', '.') : '-' }}</td>
                    <td style="padding: 12px; font-weight: bold; color: #2563eb;">
                        {{ $log->total_revenue ? 'Rp ' . number_format($log->total_revenue, 0, ',', '.') : '-' }}
                    </td>
                    <td style="padding: 12px;">{{ $log->buyer_name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada catatan panen.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.app>