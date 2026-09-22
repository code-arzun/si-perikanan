<x-layouts.app>
    <x-slot:title>Log Pakan Harian</x-slot:title>

    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
        .btn-add { background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: bold; text-decoration: none; cursor: pointer; border: none; }
        .btn-add:hover { background: #1d4ed8; }

        /* Accordion Container */
        .batch-accordion-list { display: flex; flex-direction: column; gap: 1rem; }
        .batch-card { background: white; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        
        /* Card Header / Summary */
        .batch-header { padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer; background: white; transition: background 0.2s; user-select: none; }
        .batch-header:hover { background: #f8fafc; }
        .batch-info { display: flex; align-items: center; gap: 1rem; }
        .batch-title { font-size: 1.125rem; font-weight: 700; color: #0f172a; margin: 0; }
        .batch-sub { font-size: 0.8125rem; color: #2563eb; font-weight: 600; margin-top: 0.125rem; }

        /* Stat Badges */
        .stats-group { display: flex; align-items: center; gap: 1.25rem; }
        .stat-item { text-align: right; }
        .stat-label { font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; }
        .stat-value { font-size: 1.125rem; font-weight: 700; color: #0f172a; }
        .stat-today { color: #16a34a; }

        /* Targeted Add Button */
        .btn-add-sm { background: #2563eb; color: white; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.8125rem; font-weight: 600; border: none; cursor: pointer; white-space: nowrap; }
        .btn-add-sm:hover { background: #1d4ed8; }

        /* Toggle Icon */
        .toggle-icon { font-size: 1rem; color: #64748b; transition: transform 0.3s ease; }
        .batch-card.active .toggle-icon { transform: rotate(180deg); }

        /* Accordion Content (Table Wrapper) */
        .batch-content { display: none; border-top: 1px solid #e2e8f0; background: #fafafa; padding: 1rem; }
        .batch-card.active .batch-content { display: block; }

        /* Table Style */
        .table { width: 100%; border-collapse: collapse; background: white; border-radius: 6px; overflow: hidden; font-size: 0.875rem; border: 1px solid #e2e8f0; }
        .table th { background: #f1f5f9; padding: 10px 12px; font-weight: 600; color: #475569; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #334155; }

        .badge-response { padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: capitalize; }
        .badge-good { background: #dcfce7; color: #15803d; }
        .badge-bad { background: #fee2e2; color: #991b1b; }

        /* MODAL STYLES WITH BACKDROP LOCK */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(3px); z-index: 1000; justify-content: center; align-items: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-card { background: white; width: 100%; max-width: 580px; border-radius: 10px; padding: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-height: 90vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0; }
        .btn-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b; }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Catatan Pemberian Pakan Harian</h1>
            <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">Akumulasi pakan terkelompok per batch/siklus aktif.</p>
        </div>
        <!-- Tombol Umum (Dapat Memilih Batch) -->
        {{-- <button type="button" class="btn-add" onclick="openFeedModal()">+ Catat Pakan Baru</button> --}}
        <a href="{{ route('tenant.logs.feed.bulk-create') }}" style="background: #0284c7; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: bold; text-decoration: none;">
            ⚡ Input Massal
        </a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1.5rem;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="batch-accordion-list">
        @forelse($activeBatches as $batch)
            <div class="batch-card" id="batch-card-{{ $batch->id }}">
                <!-- Card Header -->
                <div class="batch-header" onclick="toggleAccordion('batch-card-{{ $batch->id }}')">
                    <div class="batch-info">
                        <div>
                            <h3 class="batch-title">{{ $batch->pond->name ?? 'Kolam Tanpa Nama' }}</h3>
                            <div class="batch-sub">Kode Batch: {{ $batch->batch_code }}</div>
                        </div>
                    </div>

                    <div class="stats-group">
                        <div class="stat-item">
                            <div class="stat-label">Pakan Hari Ini</div>
                            <div class="stat-value stat-today">{{ number_format($batch->today_feed_kg, 2, ',', '.') }} Kg</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Total Akumulasi</div>
                            <div class="stat-value">{{ number_format($batch->total_feed_kg, 2, ',', '.') }} Kg</div>
                        </div>

                        <!-- Tombol Target Batch -->
                        <button type="button" class="btn-add-sm" 
                                onclick="event.stopPropagation(); openFeedModal({{ $batch->id }}, '{{ $batch->pond->name ?? 'Kolam' }}', '{{ $batch->batch_code }}')" 
                                title="Catat Pakan untuk {{ $batch->pond->name ?? 'Kolam ini' }}">
                            + Catat
                        </button>

                        <div class="toggle-icon">▼</div>
                    </div>
                </div>

                <!-- Card Content (Tabel Riwayat) -->
                <div class="batch-content">
                    <div style="margin-bottom: 0.75rem; font-size: 0.8125rem; color: #64748b; display: flex; justify-content: space-between; align-items: center;">
                        <span>📋 <strong>Riwayat Pakan Siklus {{ $batch->batch_code }}</strong></span>
                        @if($batch->last_feed)
                            <span>Pakan Terakhir: <strong>{{ \Carbon\Carbon::parse($batch->last_feed->feed_date)->format('d/m/Y') }}</strong></span>
                        @endif
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Pakan</th>
                                <th style="text-align: right;">Pakan / Sesi</th>
                                <th style="text-align: center;">Frekuensi</th>
                                <th style="text-align: right;">Total Harian (Kg)</th>
                                <th style="text-align: center;">Respon</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batch->dailyFeedLog as $log)
                                <tr>
                                    <td><strong>{{ \Carbon\Carbon::parse($log->feed_date)->format('d M Y') }}</strong></td>
                                    <td>{{ $log->feedType->name ?? 'Pakan Standar' }}</td>
                                    <td style="text-align: right;">{{ $log->amount_per_feed_g ? number_format($log->amount_per_feed_g, 0, ',', '.') . ' g' : '-' }}</td>
                                    <td style="text-align: center;">{{ $log->feeding_frequency }}x / hari</td>
                                    <td style="text-align: right; font-weight: bold; color: #0f172a;">
                                        {{ number_format($log->amount_kg, 2, ',', '.') }} Kg
                                    </td>
                                    <td style="text-align: center;">
                                        @php
                                            $isGood = in_array($log->appetite_response, ['sangat_baik', 'baik']);
                                        @endphp
                                        <span class="badge-response {{ $isGood ? 'badge-good' : 'badge-bad' }}">
                                            {{ str_replace('_', ' ', $log->appetite_response) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->user->name ?? 'Sistem' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #64748b; padding: 1.5rem;">
                                        Belum ada catatan pemberian pakan pada batch ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div style="background: white; border-radius: 10px; border: 1px solid #e2e8f0; padding: 3rem; text-align: center; color: #64748b;">
                Tidak ada batch/siklus aktif saat ini.
            </div>
        @endforelse
    </div>

    <!-- MODAL POP-UP FORM PAKAN -->
    <div id="feedModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3 style="margin: 0; font-size: 1.125rem; font-weight: bold; color: #0f172a;">Form Input Pakan Harian</h3>
                <button type="button" class="btn-close" onclick="closeFeedModal()">&times;</button>
            </div>

            <form action="{{ route('tenant.logs.feed.store') }}" method="POST">
                @csrf

                <!-- 1 ELEMEN UTAMA BATCH ID (Hidden) -->
                <input type="hidden" name="batch_id" id="modal_batch_id_hidden">

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Siklus / Kolam Aktif *</label>
                    
                    <!-- Terbuka saat Klik Tombol Utama -->
                    <div id="wrapper_select_batch">
                        <select id="modal_batch_id_select" onchange="document.getElementById('modal_batch_id_hidden').value = this.value" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            <option value="">-- Pilih Kolam Aktif --</option>
                            @foreach($activeBatches as $batch)
                                <option value="{{ $batch->id }}">
                                    {{ $batch->pond->name ?? '-' }} ({{ $batch->batch_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Terbuka saat Klik Tombol "+ Catat" Spesifik Batch -->
                    <div id="wrapper_locked_batch" style="display: none;">
                        <input type="text" id="modal_pond_display" readonly style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; background: #f1f5f9; color: #475569; font-weight: bold; cursor: not-allowed;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Tanggal Pakan *</label>
                        <input type="date" name="feed_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Jenis Pakan</label>
                        <select name="feed_type_id" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            <option value="">-- Pilih Jenis Pakan --</option>
                            @foreach($feedTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Frekuensi Pemberian *</label>
                        <input type="number" id="modal_freq" name="feeding_frequency" value="1" min="1" oninput="calculateModalRealtime('freq')" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Pakan / Sesi (Gram)</label>
                        <input type="text" inputmode="decimal" id="modal_per_feed" name="amount_per_feed_g" placeholder="Contoh: 500" oninput="calculateModalRealtime('per_feed')" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Total Pakan Harian (Kg) *</label>
                        <input type="text" inputmode="decimal" id="modal_total_kg" name="amount_kg" placeholder="Contoh: 1,5" oninput="calculateModalRealtime('total_kg')" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Respon Pakan *</label>
                        <select name="appetite_response" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            <option value="sangat_baik">Sangat Baik</option>
                            <option value="baik" selected>Baik</option>
                            <option value="kurang">Kurang</option>
                            <option value="buruk">Buruk</option>
                        </select>
                    </div>
                </div>

                <!-- Preview Realtime -->
                <div id="modal_realtime_preview" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                    <div style="font-size: 0.8rem; color: #1e40af; font-weight: bold;">Kalkulasi Pakan:</div>
                    <div style="display: flex; gap: 1rem; margin-top: 2px; font-size: 0.85rem; color: #1e3a8a;">
                        <div>Sesi: <strong id="preview_per_feed">0</strong> g</div>
                        <div>Frekuensi: <strong id="preview_freq">0</strong>x</div>
                        <div>Total: <strong id="preview_total_kg">0</strong> Kg</div>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Catatan</label>
                    <textarea name="notes" rows="2" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Catatan opsional..."></textarea>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeFeedModal()" style="background: #9ca3af; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Batal</button>
                    <button type="submit" style="background: #2563eb; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Log</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Toggle Accordion
        function toggleAccordion(cardId) {
            const card = document.getElementById(cardId);
            card.classList.toggle('active');
        }

        // Open Modal Function
        function openFeedModal(batchId = null, pondName = '', batchCode = '') {
            const selectWrapper = document.getElementById('wrapper_select_batch');
            const lockedWrapper = document.getElementById('wrapper_locked_batch');
            const hiddenBatchInput = document.getElementById('modal_batch_id_hidden');
            const selectBatchInput = document.getElementById('modal_batch_id_select');

            if (batchId) {
                // Klik dari Tombol Target Card Kolam
                selectWrapper.style.display = 'none';
                lockedWrapper.style.display = 'block';
                
                hiddenBatchInput.value = batchId;
                document.getElementById('modal_pond_display').value = pondName + ' (' + batchCode + ')';
            } else {
                // Klik dari Tombol Utama
                lockedWrapper.style.display = 'none';
                selectWrapper.style.display = 'block';
                
                selectBatchInput.value = '';
                hiddenBatchInput.value = '';
            }

            document.getElementById('feedModal').classList.add('active');
        }

        // Close Modal Function
        function closeFeedModal() {
            document.getElementById('feedModal').classList.remove('active');
        }

        // Parse Float Helper
        function parseValue(val) {
            if (!val) return 0;
            return parseFloat(val.toString().replace(',', '.')) || 0;
        }

        function formatDecimal(num) {
            return num.toFixed(2).replace('.', ',');
        }

        // Realtime Calculation inside Modal
        function calculateModalRealtime(source) {
            const freq = Math.max(1, parseValue(document.getElementById('modal_freq').value));
            let perFeedG = parseValue(document.getElementById('modal_per_feed').value);
            let totalKg = parseValue(document.getElementById('modal_total_kg').value);

            if (source === 'per_feed' || source === 'freq') {
                if (perFeedG > 0) {
                    totalKg = (perFeedG * freq) / 1000;
                    document.getElementById('modal_total_kg').value = formatDecimal(totalKg);
                }
            } else if (source === 'total_kg') {
                if (totalKg > 0 && freq > 0) {
                    perFeedG = (totalKg * 1000) / freq;
                    document.getElementById('modal_per_feed').value = formatDecimal(perFeedG);
                }
            }

            const previewBox = document.getElementById('modal_realtime_preview');
            if (perFeedG > 0 || totalKg > 0) {
                previewBox.style.display = 'block';
                document.getElementById('preview_per_feed').innerText = formatDecimal(perFeedG);
                document.getElementById('preview_freq').innerText = freq;
                document.getElementById('preview_total_kg').innerText = formatDecimal(totalKg);
            } else {
                previewBox.style.display = 'none';
            }
        }
    </script>
</x-layouts.app>