<x-layouts.app>
    <x-slot:title>Detail Batch {{ $batch->batch_code }}</x-slot:title>

    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
        .btn-back { background: #64748b; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: bold; text-decoration: none; font-size: 0.875rem; }

        /* Badge Status */
        .badge-status { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
        .badge-aktif { background: #d1fae5; color: #065f46; }
        .badge-panen { background: #dbeafe; color: #1e40af; }
        .badge-gagal { background: #fee2e2; color: #991b1b; }

        /* Summary Header Box */
        .batch-summary-box { background: white; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; }

        /* Metric KPI Cards Grid */
        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .kpi-card { background: white; border-radius: 10px; border: 1px solid #e2e8f0; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .kpi-title { font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; }
        .kpi-value { font-size: 1.5rem; font-weight: 800; margin-top: 0.25rem; }

        /* Tab Navigation */
        .tab-container { background: white; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .tab-menu { display: flex; background: #f8fafc; border-bottom: 1px solid #e2e8f0; overflow-x: auto; }
        .tab-btn { padding: 12px 20px; border: none; background: none; font-weight: 600; font-size: 0.875rem; color: #64748b; cursor: pointer; white-space: nowrap; border-bottom: 2px solid transparent; transition: all 0.2s; }
        .tab-btn.active { color: #2563eb; border-bottom-color: #2563eb; background: white; }
        .tab-content { padding: 1.25rem; display: none; }
        .tab-content.active { display: block; }

        /* Table Style Inside Tab */
        .table { width: 100%; border-collapse: collapse; font-size: 0.875rem; text-align: left; }
        .table th { background: #f1f5f9; padding: 10px 12px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #334155; }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">
                🏊‍♂️ {{ $batch->pond->name ?? 'Kolam' }} 
                <span style="font-weight: normal; color: #64748b;">({{ $batch->batch_code }})</span>
            </h1>
            <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">
                Komoditas: <strong>{{ $batch->fishSpecies->name ?? 'Ikan/Udang' }}</strong> | Tanggal Tebar: {{ \Carbon\Carbon::parse($batch->start_date)->format('d M Y') }}
            </p>
        </div>
        <a href="{{ route('tenant.batches.index') ?? '#' }}" class="btn-back">← Kembali ke Daftar Batch</a>
    </div>

    <!-- BATCH SUMMARY BOX -->
    <div class="batch-summary-box">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span class="badge-status badge-{{ $batch->status }}">{{ $batch->status }}</span>
                <span style="font-size: 0.875rem; color: #64748b; margin-left: 8px;">
                    Target Harvest: {{ $batch->estimated_harvest_date ? \Carbon\Carbon::parse($batch->estimated_harvest_date)->format('d M Y') : '-' }}
                </span>
            </div>
            @if($batch->notes)
                <small style="color: #64748b; font-style: italic;">Catatan: {{ $batch->notes }}</small>
            @endif
        </div>

        <div class="info-grid">
            <div>
                <small style="color: #64748b;">Tebar Awal</small>
                <div style="font-weight: bold; color: #0f172a;">{{ number_format($batch->initial_seed_count, 0, ',', '.') }} Ekor</div>
            </div>
            <div>
                <small style="color: #64748b;">MBW Awal</small>
                <div style="font-weight: bold; color: #0f172a;">{{ number_format($batch->initial_avg_weight_g, 2, ',', '.') }} Gram</div>
            </div>
            <div>
                <small style="color: #64748b;">Biomassa Awal</small>
                <div style="font-weight: bold; color: #0f172a;">{{ number_format($batch->initial_total_weight_kg, 2, ',', '.') }} Kg</div>
            </div>
            <div>
                <small style="color: #64748b;">Target FCR / SR</small>
                <div style="font-weight: bold; color: #0f172a;">{{ $batch->target_fcr }} / {{ $batch->target_survival_rate }}%</div>
            </div>
        </div>
    </div>

    <!-- METRIC KPI CARDS -->
    <div class="kpi-grid">
        <div class="kpi-card" style="border-top: 4px solid #2563eb;">
            <div class="kpi-title">Umur Budidaya (DOC)</div>
            <div class="kpi-value" style="color: #2563eb;">{{ $doc }} <span style="font-size: 0.875rem;">Hari</span></div>
        </div>

        <div class="kpi-card" style="border-top: 4px solid #10b981;">
            <div class="kpi-title">Survival Rate (SR)</div>
            <div class="kpi-value" style="color: #059669;">{{ $survivalRate }}%</div>
            <small style="color: #64748b;">Estimasi {{ number_format($currentPopulationPcs, 0, ',', '.') }} ekor hidup</small>
        </div>

        <div class="kpi-card" style="border-top: 4px solid #0284c7;">
            <div class="kpi-title">MBW Terkini</div>
            <div class="kpi-value" style="color: #0284c7;">{{ number_format($latestMbwG, 2, ',', '.') }} <span style="font-size: 0.875rem;">Gram</span></div>
        </div>

        <div class="kpi-card" style="border-top: 4px solid #8b5cf6;">
            <div class="kpi-title">Estimasi Biomassa</div>
            <div class="kpi-value" style="color: #7c3aed;">{{ number_format($currentBiomassKg, 2, ',', '.') }} <span style="font-size: 0.875rem;">Kg</span></div>
        </div>

        <div class="kpi-card" style="border-top: 4px solid #f59e0b;">
            <div class="kpi-title">Total Pakan Terpakai</div>
            <div class="kpi-value" style="color: #d97706;">{{ number_format($totalFeedKg, 2, ',', '.') }} <span style="font-size: 0.875rem;">Kg</span></div>
        </div>

        <div class="kpi-card" style="border-top: 4px solid #ec4899;">
            <div class="kpi-title">FCR Sementara</div>
            <div class="kpi-value" style="color: #db2777;">{{ $fcr }}</div>
            <small style="color: #64748b;">Target: {{ $batch->target_fcr }}</small>
        </div>
    </div>

    <!-- TABBED LOG HISTORY -->
    <div class="tab-container">
        <div class="tab-menu">
            <button class="tab-btn active" onclick="switchTab('feed')">🥣 Log Pakan ({{ $batch->dailyFeedLog->count() }})</button>
            <button class="tab-btn" onclick="switchTab('water')">💧 Kualitas Air ({{ $batch->waterQualityLog->count() }})</button>
            <button class="tab-btn" onclick="switchTab('sampling')">📏 Sampling ({{ $batch->samplingLog->count() }})</button>
            <button class="tab-btn" onclick="switchTab('mortality')">💀 Kematian ({{ $batch->mortalityLog->count() }})</button>
            <button class="tab-btn" onclick="switchTab('treatment')">🌿 Treatment ({{ $batch->treatmentLog->count() }})</button>
        </div>

        <!-- 1. TAB LOG PAKAN -->
        <div id="tab-feed" class="tab-content active">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jumlah Pakan (Kg)</th>
                        <th>Frekuensi</th>
                        <th>Respon Makan</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batch->dailyFeedLog as $log)
                        <tr>
                            <td><strong>{{ \Carbon\Carbon::parse($log->feed_date)->format('d M Y') }}</strong></td>
                            <td style="font-weight: bold; color: #d97706;">{{ number_format($log->amount_kg, 2, ',', '.') }} Kg</td>
                            <td>{{ $log->feeding_frequency }}x / hari</td>
                            <td>{{ $log->appetite_response ?? '-' }}</td>
                            <td>{{ $log->user->name ?? 'Sistem' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; color: #64748b; padding: 1.5rem;">Belum ada catatan pakan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 2. TAB KUALITAS AIR -->
        <div id="tab-water" class="tab-content">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal / Sesi</th>
                        <th>pH</th>
                        <th>DO (mg/L)</th>
                        <th>Suhu (°C)</th>
                        <th>Kecerahan (cm)</th>
                        <th>Warna Air</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batch->waterQualityLog as $log)
                        <tr>
                            <td><strong>{{ \Carbon\Carbon::parse($log->check_date)->format('d M Y') }}</strong> ({{ ucfirst($log->check_time_session) }})</td>
                            <td>{{ $log->ph ?? '-' }}</td>
                            <td>{{ $log->do_mg_l ?? '-' }}</td>
                            <td>{{ $log->temperature_c ? $log->temperature_c . ' °C' : '-' }}</td>
                            <td>{{ $log->transparency_cm ? $log->transparency_cm . ' cm' : '-' }}</td>
                            <td>{{ $log->water_color ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align: center; color: #64748b; padding: 1.5rem;">Belum ada catatan kualitas air.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 3. TAB SAMPLING -->
        <div id="tab-sampling" class="tab-content">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal Sampling</th>
                        <th>Jumlah Sampel</th>
                        <th>MBW / Rata-rata (Gram)</th>
                        <th>Panjang Rata-rata</th>
                        <th>Estimasi Biomassa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batch->samplingLog as $log)
                        <tr>
                            <td><strong>{{ \Carbon\Carbon::parse($log->sampling_date)->format('d M Y') }}</strong></td>
                            <td>{{ number_format($log->sample_count_pcs, 0, ',', '.') }} ekor</td>
                            <td style="font-weight: bold; color: #0284c7;">{{ number_format($log->avg_weight_g, 2, ',', '.') }} g</td>
                            <td>{{ $log->avg_length_cm ? $log->avg_length_cm . ' cm' : '-' }}</td>
                            <td>{{ $log->estimated_biomass_kg ? number_format($log->estimated_biomass_kg, 2, ',', '.') . ' Kg' : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; color: #64748b; padding: 1.5rem;">Belum ada catatan sampling.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 4. TAB KEMATIAN -->
        <div id="tab-mortality" class="tab-content">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jumlah Mati</th>
                        <th>Total Bobot (Gram)</th>
                        <th>Indikasi / Penyebab</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batch->mortalityLog as $log)
                        <tr>
                            <td><strong>{{ \Carbon\Carbon::parse($log->log_date)->format('d M Y') }}</strong></td>
                            <td style="font-weight: bold; color: #dc2626;">{{ number_format($log->quantity_pcs, 0, ',', '.') }} ekor</td>
                            <td>{{ $log->total_weight_g ? number_format($log->total_weight_g, 0, ',', '.') . ' g' : '-' }}</td>
                            <td>{{ $log->indication ?? '-' }}</td>
                            <td>{{ $log->action_taken ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; color: #64748b; padding: 1.5rem;">Belum ada catatan kematian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 5. TAB TREATMENT -->
        <div id="tab-treatment" class="tab-content">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Bahan / Produk</th>
                        <th>Dosis</th>
                        <th>Tujuan</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batch->treatmentLog as $log)
                        <tr>
                            <td><strong>{{ \Carbon\Carbon::parse($log->treatment_date)->format('d M Y') }}</strong></td>
                            <td><strong>{{ $log->product_name }}</strong></td>
                            <td style="font-weight: bold; color: #059669;">{{ number_format($log->dosage_amount, 2, ',', '.') }} {{ $log->dosage_unit }}</td>
                            <td>{{ $log->purpose ?? '-' }}</td>
                            <td>{{ $log->user->name ?? 'Sistem' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; color: #64748b; padding: 1.5rem;">Belum ada catatan treatment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- JAVASCRIPT TAB SWITCHER -->
    <script>
        function switchTab(tabName) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            // Deactivate all buttons
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            // Activate targeted tab and button
            document.getElementById('tab-' + tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }
    </script>
</x-layouts.app>