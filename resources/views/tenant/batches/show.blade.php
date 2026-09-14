<x-layouts.app>
    <x-slot:title>Detail Siklus {{ $batch->batch_code }}</x-slot:title>

    <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <a href="/tenant/batches" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">&larr; Kembali ke Daftar Siklus</a>
            <h2 style="margin-top: 4px;">Siklus: {{ $batch->batch_code }}</h2>
            <span style="color: #4b5563;">Kolam: <strong>{{ $batch->pond->name ?? '-' }}</strong> | Spesies: <strong>{{ $batch->fishSpecies->name ?? '-' }}</strong></span>
        </div>
        <div>
            <span style="padding: 6px 12px; border-radius: 20px; font-weight: bold; font-size: 0.85rem; background: {{ $batch->status === 'aktif' ? '#dcfce7' : '#f3f4f6' }}; color: {{ $batch->status === 'aktif' ? '#15803d' : '#4b5563' }}; text-transform: uppercase;">
                Status: {{ $batch->status }}
            </span>
        </div>
    </div>

    {{-- Grid KPI Ringkasan Budidaya (5 Card) --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        
        {{-- Card 1: FCR --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #2563eb;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold;">FCR (Feed Ratio)</div>
            <div style="font-size: 1.6rem; font-weight: bold; color: #1e3a8a; margin: 4px 0;">{{ $currentFcr > 0 ? $currentFcr : '-' }}</div>
            <small style="color: #6b7280;">Target: {{ $batch->target_fcr ?? '1.2' }}</small>
        </div>

        {{-- Card 2: Survival Rate % --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #16a34a;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold;">Survival Rate (SR)</div>
            <div style="font-size: 1.6rem; font-weight: bold; color: #15803d; margin: 4px 0;">{{ $survivalRate }}%</div>
            <small style="color: #6b7280;">Sisa Kolam: {{ number_format($currentPopulation) }} ekor</small>
        </div>

        {{-- Card 3: Est. Biomasa Aktif Kolam --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #8b5cf6;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold;">Biomasa Kolam (Aktif)</div>
            <div style="font-size: 1.6rem; font-weight: bold; color: #6d28d9; margin: 4px 0;">{{ number_format($currentBiomassKg, 1, ',', '.') }} <span style="font-size: 0.9rem;">Kg</span></div>
            <small style="color: #6b7280;">MBW: {{ number_format($latestMbwG, 1, ',', '.') }}g</small>
        </div>

        {{-- Card 4: Total Pakan --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold;">Total Pakan Terpakai</div>
            <div style="font-size: 1.6rem; font-weight: bold; color: #b45309; margin: 4px 0;">{{ number_format($totalFeedKg, 1, ',', '.') }} <span style="font-size: 0.9rem;">Kg</span></div>
            <small style="color: #6b7280;">Log Pakan Harian</small>
        </div>

        {{-- Card 5: Hasil Panen & Omzet --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #059669;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold;">Total Hasil Panen</div>
            <div style="font-size: 1.6rem; font-weight: bold; color: #047857; margin: 4px 0;">{{ number_format($totalHarvestKg, 1, ',', '.') }} <span style="font-size: 0.9rem;">Kg</span></div>
            <small style="color: #059669; font-weight: bold;">Omzet: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</small>
        </div>

    </div>

    {{-- Tabel Riwayat Panen --}}
    <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="margin: 0; color: #047857;">Riwayat Panen (Parsial & Total)</h3>
            <a href="/tenant/harvests/create" style="background: #10b981; color: white; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: bold;">+ Catat Panen</a>
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9fafb; text-align: left; border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 10px;">Tanggal</th>
                    <th style="padding: 10px;">Tipe</th>
                    <th style="padding: 10px;">Hasil Panen (Kg)</th>
                    <th style="padding: 10px;">Jumlah Ekor</th>
                    <th style="padding: 10px;">Harga / Kg</th>
                    <th style="padding: 10px;">Total Pendapatan</th>
                    <th style="padding: 10px;">Pembeli</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batch->harvestLog as $log)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 10px;">{{ \Carbon\Carbon::parse($log->harvest_date)->format('d/m/Y') }}</td>
                        <td style="padding: 10px;">
                            <span style="padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: bold; background: {{ $log->harvest_type === 'total' ? '#fee2e2' : '#e0e7ff' }}; color: {{ $log->harvest_type === 'total' ? '#991b1b' : '#3730a3' }}; text-transform: uppercase;">
                                {{ $log->harvest_type === 'total' ? 'Total' : 'Parsial' }}
                            </span>
                        </td>
                        <td style="padding: 10px; font-weight: bold; color: #047857;">{{ number_format($log->weight_kg, 2, ',', '.') }} Kg</td>
                        <td style="padding: 10px;">{{ $log->total_pcs ? number_format($log->total_pcs) . ' ekor' : '-' }}</td>
                        <td style="padding: 10px;">{{ $log->price_per_kg ? 'Rp ' . number_format($log->price_per_kg, 0, ',', '.') : '-' }}</td>
                        <td style="padding: 10px; font-weight: bold; color: #2563eb;">{{ $log->total_revenue ? 'Rp ' . number_format($log->total_revenue, 0, ',', '.') : '-' }}</td>
                        <td style="padding: 10px; color: #6b7280;">{{ $log->buyer_name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 15px; text-align: center; color: #6b7280;">Belum ada catatan panen untuk siklus ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Grid 3 Kolom: Log Pakan, Kematian, & Sampling --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        
        {{-- Log Pakan --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 0.75rem; color: #b45309; font-size: 1.1rem;">Log Pakan Terbaru</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #f9fafb; text-align: left; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 8px;">Tgl & Jam</th>
                        <th style="padding: 8px;">Jumlah</th>
                        <th style="padding: 8px;">Respon</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batch->dailyFeedLog as $log)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px;">{{ \Carbon\Carbon::parse($log->feed_date)->format('d/m') }} ({{ $log->feed_time }})</td>
                            <td style="padding: 8px; font-weight: bold;">{{ number_format($log->amount_kg, 2, ',', '.') }} Kg</td>
                            <td style="padding: 8px; text-transform: capitalize;">{{ str_replace('_', ' ', $log->appetite_response) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 10px; text-align: center; color: #6b7280;">Belum ada log pakan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Log Kematian --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 0.75rem; color: #dc2626; font-size: 1.1rem;">Log Kematian Terbaru</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #f9fafb; text-align: left; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 8px;">Tanggal</th>
                        <th style="padding: 8px;">Jumlah</th>
                        <th style="padding: 8px;">Indikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batch->mortalityLog as $log)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px;">{{ \Carbon\Carbon::parse($log->log_date)->format('d/m/Y') }}</td>
                            <td style="padding: 8px; color: #dc2626; font-weight: bold;">{{ number_format($log->quantity_pcs) }} ekor</td>
                            <td style="padding: 8px; color: #6b7280;">{{ $log->indication ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 10px; text-align: center; color: #6b7280;">Tidak ada kematian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Log Sampling --}}
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 0.75rem; color: #2563eb; font-size: 1.1rem;">Log Sampling Terbaru</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #f9fafb; text-align: left; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 8px;">Tanggal</th>
                        <th style="padding: 8px;">MBW</th>
                        <th style="padding: 8px;">Est. Biomasa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batch->samplingLog as $log)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px;">{{ \Carbon\Carbon::parse($log->sampling_date)->format('d/m/Y') }}</td>
                            <td style="padding: 8px; font-weight: bold; color: #2563eb;">{{ number_format($log->avg_weight_g, 2, ',', '.') }} g</td>
                            <td style="padding: 8px;">{{ number_format($log->estimated_biomass_kg, 1, ',', '.') }} Kg</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 10px; text-align: center; color: #6b7280;">Belum ada sampling.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-layouts.app>