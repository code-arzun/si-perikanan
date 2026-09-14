<x-layouts.app>
    <x-slot:title>Siklus Budidaya</x-slot:title>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Daftar Siklus Budidaya (Batch)</h2>
        <a href="/tenant/batches/create" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: bold;">+ Mulai Siklus Baru</a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #f3f4f6; text-align: left; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 12px;">Kode & Spesies</th>
                <th style="padding: 12px;">Kolam</th>
                <th style="padding: 12px;">Tgl Tebar</th>
                <th style="padding: 12px;">Tebar Awal</th>
                <th style="padding: 12px;">Estimasi Saat Ini</th>
                <th style="padding: 12px;">Status</th>
                <th style="padding: 12px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($batches as $batch)
                @php
                    $totalMati = $batch->mortality_log_sum_quantity_pcs ?? 0;
                    $totalParsial = $batch->harvest_log_sum_total_pcs ?? 0;
                    $estimasiSaatIni = max(0, $batch->initial_seed_count - $totalMati - $totalParsial);
                @endphp
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">
                        <strong>{{ $batch->batch_code }}</strong><br>
                        <small style="color: #6b7280;">{{ $batch->fishSpecies->name ?? '-' }}</small>
                    </td>
                    <td style="padding: 12px;">
                        <strong>{{ $batch->pond->name ?? '-' }}</strong>
                    </td>
                    <td style="padding: 12px;">
                        {{ \Carbon\Carbon::parse($batch->start_date)->format('d/m/Y') }}
                    </td>
                    <td style="padding: 12px;">
                        {{ number_format($batch->initial_seed_count) }} ekor
                    </td>
                    <td style="padding: 12px; color: #16a34a; font-weight: bold;">
                        {{ number_format($estimasiSaatIni) }} ekor
                    </td>
                    <td style="padding: 12px; text-transform: uppercase;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background: {{ $batch->status === 'aktif' ? '#dcfce7' : '#f3f4f6' }}; color: {{ $batch->status === 'aktif' ? '#15803d' : '#4b5563' }};">
                            {{ $batch->status }}
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="/tenant/batches/{{ $batch->id }}" title="Lihat Detail Siklus" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #eff6ff; color: #2563eb; border-radius: 6px; text-decoration: none;">
                            {{-- Icon Eye (SVG) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 24px; text-align: center; color: #6b7280;">Belum ada siklus budidaya aktif.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.app>