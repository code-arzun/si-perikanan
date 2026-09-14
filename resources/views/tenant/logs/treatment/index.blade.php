<x-layouts.app>
    <x-slot:title>Log Treatment & Obat</x-slot:title>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Catatan Treatment, Kapur & Probiotik</h2>
        <a href="/tenant/logs/treatment/create" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: bold;">+ Catat Treatment Baru</a>
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
                <th style="padding: 12px;">Bahan / Produk</th>
                <th style="padding: 12px;">Dosis</th>
                <th style="padding: 12px;">Tujuan Treatment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($treatmentLogs as $log)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;"><strong>{{ \Carbon\Carbon::parse($log->treatment_date)->format('d M Y') }}</strong></td>
                    <td style="padding: 12px;">
                        <strong>{{ $log->batch->pond->name ?? '-' }}</strong><br>
                        <small style="color: #2563eb;">{{ $log->batch->batch_code ?? '-' }}</small>
                    </td>
                    <td style="padding: 12px;"><strong>{{ $log->product_name }}</strong></td>
                    <td style="padding: 12px; color: #059669; font-weight: bold;">{{ number_format($log->dosage_amount, 2, ',', '.') }} {{ $log->dosage_unit }}</td>
                    <td style="padding: 12px;">{{ $log->purpose ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada catatan treatment air/kolam.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.app>