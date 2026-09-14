<x-layouts.app>
    <x-slot:title>Dashboard Operasional</x-slot:title>

    <div style="margin-bottom: 1.5rem;">
        <h2 style="margin: 0;">Dashboard Utama Farm</h2>
        <span style="color: #6b7280; font-size: 0.9rem;">Ringkasan operasional budidaya dan performa seluruh kolam.</span>
    </div>

    {{-- Grid KPI Utama --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        
        {{-- Card 1: Kolam Aktif --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #2563eb;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold; text-transform: uppercase;">Status Kolam</div>
            <div style="font-size: 1.8rem; font-weight: bold; color: #1e3a8a; margin: 4px 0;">{{ $activePondsCount }} <span style="font-size: 1rem; font-weight: normal; color: #6b7280;">/ {{ $totalPondsCount }} Aktif</span></div>
            <small style="color: #059669; font-weight: bold;">{{ $emptyPondsCount }} Kolam Kosong Siap Tebar</small>
        </div>

        {{-- Card 2: Total Biomasa Aktif --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #8b5cf6;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold; text-transform: uppercase;">Total Biomasa Ikan</div>
            <div style="font-size: 1.8rem; font-weight: bold; color: #6d28d9; margin: 4px 0;">{{ number_format($totalActiveBiomassKg, 1, ',', '.') }} <span style="font-size: 1rem;">Kg</span></div>
            <small style="color: #6b7280;">Populasi: {{ number_format($totalActivePopulationPcs) }} ekor</small>
        </div>

        {{-- Card 3: Pakan Hari Ini --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold; text-transform: uppercase;">Pakan Hari Ini</div>
            <div style="font-size: 1.8rem; font-weight: bold; color: #b45309; margin: 4px 0;">{{ number_format($totalFeedTodayKg, 1, ',', '.') }} <span style="font-size: 1rem;">Kg</span></div>
            <small style="color: #6b7280;">Bulan Ini: {{ number_format($totalFeedThisMonthKg, 1, ',', '.') }} Kg</small>
        </div>

        {{-- Card 4: Penjualan Bulan Ini --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold; text-transform: uppercase;">Omzet Panen (Bulan Ini)</div>
            <div style="font-size: 1.6rem; font-weight: bold; color: #047857; margin: 4px 0;">Rp {{ number_format($totalRevenueThisMonth, 0, ',', '.') }}</div>
            <small style="color: #059669; font-weight: bold;">Pencatatan Panen</small>
        </div>

    </div>

    {{-- Layout 2 Kolom: Ringkasan Kolam Aktif & Konsumsi Pakan 7 Hari --}}
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        
        {{-- Kolom Kiri: Tabel Performa Kolam Aktif --}}
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0;">Siklus & Kolam Aktif</h3>
                <a href="/tenant/batches/create" style="color: #2563eb; text-decoration: none; font-size: 0.85rem; font-weight: bold;">+ Tambah Siklus Baru</a>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #f9fafb; text-align: left; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 10px;">Kolam / Kode</th>
                        <th style="padding: 10px;">Spesies</th>
                        <th style="padding: 10px;">Sisa Populasi</th>
                        <th style="padding: 10px;">MBW</th>
                        <th style="padding: 10px;">Biomasa</th>
                        <th style="padding: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeBatches as $batch)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 10px;">
                                <strong>{{ $batch->pond->name ?? '-' }}</strong><br>
                                <small style="color: #2563eb;">{{ $batch->batch_code }}</small>
                            </td>
                            <td style="padding: 10px;">{{ $batch->fishSpecies->name ?? '-' }}</td>
                            <td style="padding: 10px; font-weight: bold;">{{ number_format($batch->calculated_population) }} ekor</td>
                            <td style="padding: 10px;">{{ number_format($batch->calculated_mbw, 1, ',', '.') }} g</td>
                            <td style="padding: 10px; font-weight: bold; color: #6d28d9;">{{ number_format($batch->calculated_biomass, 1, ',', '.') }} Kg</td>
                            <td style="padding: 10px; text-align: center;">
                                <a href="/tenant/batches/{{ $batch->id }}" title="Lihat Detail Siklus" style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; background: #eff6ff; color: #2563eb; border-radius: 6px; text-decoration: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 20px; text-align: center; color: #6b7280;">Tidak ada siklus budidaya yang sedang aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Kolom Kanan: Tren Konsumsi Pakan 7 Hari --}}
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 1rem; color: #b45309;">Penggunaan Pakan 7 Hari Terakhir</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #f9fafb; text-align: left; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 8px;">Tanggal</th>
                        <th style="padding: 8px; text-align: right;">Total Pakan (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedStatsLast7Days as $stat)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px;">{{ \Carbon\Carbon::parse($stat->feed_date)->format('d M Y') }}</td>
                            <td style="padding: 8px; text-align: right; font-weight: bold; color: #d97706;">{{ number_format($stat->total_kg, 2, ',', '.') }} Kg</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="padding: 15px; text-align: center; color: #6b7280;">Belum ada data pakan 7 hari terakhir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-layouts.app>