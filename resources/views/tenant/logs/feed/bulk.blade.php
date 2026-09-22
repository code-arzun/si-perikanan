<x-layouts.app>
    <x-slot:title>Input Pakan Massal</x-slot:title>

    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
        
        .card-container { background: white; border-radius: 10px; border: 1px solid #e2e8f0; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        
        /* Master Control Box */
        .master-box { background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem; }
        .master-title { font-size: 0.9375rem; font-weight: 700; color: #1e293b; margin-top: 0; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }

        /* Table Styling */
        .table-wrapper { overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 1.5rem; }
        .table { width: 100%; border-collapse: collapse; background: white; font-size: 0.875rem; }
        .table th { background: #f1f5f9; padding: 10px 12px; font-weight: 600; color: #475569; text-align: left; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        .table tr:hover { background: #fafafa; }

        .input-control { width: 100%; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.875rem; }
        .input-control:disabled { background: #f1f5f9; cursor: not-allowed; opacity: 0.6; }

        .btn-primary { background: #2563eb; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; font-weight: 700; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { background: #64748b; color: white; padding: 0.625rem 1.25rem; border-radius: 6px; font-weight: 700; text-decoration: none; }
        .btn-secondary:hover { background: #475569; }
        .btn-apply { background: #0284c7; color: white; padding: 6px 12px; border-radius: 4px; font-weight: 600; border: none; cursor: pointer; font-size: 0.8125rem; }
        .btn-apply:hover { background: #0369a1; }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">⚡ Input Pakan Massal</h1>
            <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">Pemberian pakan sekaligus untuk beberapa kolam/siklus aktif.</p>
        </div>
        <a href="{{ route('tenant.logs.feed.index') }}" class="btn-secondary">← Kembali ke Log Pakan</a>
    </div>

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1.5rem;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tenant.logs.feed.bulk-store') }}" method="POST">
        @csrf

        <div class="card-container">
            <!-- MASTER CONTROL / PARAMETER GLOBAL -->
            <div class="master-box">
                <div class="master-title">⚙️ Parameter Utama & Penerapan Cepat</div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; align-items: end;">
                    <div>
                        <label style="display: block; font-weight: bold; font-size: 0.8125rem; margin-bottom: 4px;">Tanggal Pakan *</label>
                        <input type="date" name="feed_date" value="{{ date('Y-m-d') }}" required class="input-control">
                    </div>

                    <div>
                        <label style="display: block; font-weight: bold; font-size: 0.8125rem; margin-bottom: 4px;">Jenis Pakan Global</label>
                        <select name="feed_type_id" id="master_feed_type" class="input-control">
                            <option value="">-- Pilih Jenis Pakan --</option>
                            @foreach($feedTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-weight: bold; font-size: 0.8125rem; margin-bottom: 4px;">Frekuensi Global *</label>
                        <input type="number" id="master_freq" name="feeding_frequency" value="1" min="1" required class="input-control">
                    </div>

                    <div>
                        <label style="display: block; font-weight: bold; font-size: 0.8125rem; margin-bottom: 4px;">Pakan / Sesi Global (Gram)</label>
                        <input type="text" inputmode="decimal" id="master_per_feed" placeholder="Contoh: 500" class="input-control">
                    </div>

                    <div>
                        <button type="button" class="btn-apply" onclick="applyMasterToAll()" style="width: 100%; height: 35px;">
                            📋 Salin Nilai ke Semua Kolam
                        </button>
                    </div>
                </div>
            </div>

            <!-- TABEL MATRIX LIST KOLAM AKTIF -->
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">
                                <input type="checkbox" id="check_all" checked onclick="toggleCheckAll(this)">
                            </th>
                            <th>Kolam / Kode Batch</th>
                            <th style="width: 110px;">Frekuensi</th>
                            <th style="width: 160px;">Pakan / Sesi (Gram)</th>
                            <th style="width: 140px;">Total Harian (Kg) *</th>
                            <th style="width: 150px;">Respon Pakan</th>
                            <th>Catatan Opsional</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeBatches as $index => $batch)
                            <tr id="row_{{ $batch->id }}">
                                <!-- Checkbox -->
                                <td style="text-align: center;">
                                    <input type="checkbox" 
                                        name="logs[{{ $index }}][enabled]" 
                                        value="1" 
                                        checked 
                                        class="row-checkbox" 
                                        onchange="toggleRowState({{ $batch->id }}, this.checked)">
                                    
                                    <input type="hidden" name="logs[{{ $index }}][batch_id]" value="{{ $batch->id }}">
                                </td>

                                <!-- Info Batch -->
                                <td>
                                    <strong>{{ $batch->pond->name ?? 'Kolam Tanpa Nama' }}</strong>
                                    <div style="font-size: 0.75rem; color: #2563eb;">Batch: {{ $batch->batch_code }}</div>
                                </td>

                                <!-- Frekuensi Per Baris (Manual Input) -->
                                <td>
                                    <input type="number" 
                                        min="1" 
                                        id="freq_{{ $batch->id }}" 
                                        name="logs[{{ $index }}][feeding_frequency]" 
                                        value="1" 
                                        class="input-control input-row-{{ $batch->id }}" 
                                        oninput="calculateRowRealtime({{ $batch->id }}, 'freq')">
                                </td>

                                <!-- Pakan / Sesi (Gram) -->
                                <td>
                                    <input type="text" 
                                        inputmode="decimal" 
                                        id="per_feed_{{ $batch->id }}" 
                                        name="logs[{{ $index }}][amount_per_feed_g]" 
                                        placeholder="0" 
                                        class="input-control input-row-{{ $batch->id }}" 
                                        oninput="calculateRowRealtime({{ $batch->id }}, 'per_feed')">
                                </td>

                                <!-- Total Kg -->
                                <td>
                                    <input type="text" 
                                        inputmode="decimal" 
                                        id="total_kg_{{ $batch->id }}" 
                                        name="logs[{{ $index }}][amount_kg]" 
                                        placeholder="0" 
                                        class="input-control input-row-{{ $batch->id }}" 
                                        oninput="calculateRowRealtime({{ $batch->id }}, 'total_kg')">
                                </td>

                                <!-- Respon Pakan -->
                                <td>
                                    <select name="logs[{{ $index }}][appetite_response]" class="input-control input-row-{{ $batch->id }}">
                                        <option value="sangat_baik">Sangat Baik</option>
                                        <option value="baik" selected>Baik</option>
                                        <option value="kurang">Kurang</option>
                                        <option value="buruk">Buruk</option>
                                    </select>
                                </td>

                                <!-- Catatan -->
                                <td>
                                    <input type="text" 
                                        name="logs[{{ $index }}][notes]" 
                                        placeholder="Keterangan..." 
                                        class="input-control input-row-{{ $batch->id }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: #64748b;">
                                    Tidak ada batch/siklus aktif saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ACTION BUTTONS -->
            @if($activeBatches->count() > 0)
                <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route('tenant.logs.feed.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan Semua Log Pakan</button>
                </div>
            @endif
        </div>
    </form>

    <!-- JAVASCRIPT BULK LOGIC -->
    <script>
        // Parse float Helper
        function parseValue(val) {
            if (!val) return 0;
            return parseFloat(val.toString().replace(',', '.')) || 0;
        }

        function formatDecimal(num) {
            return num.toFixed(2).replace('.', ',');
        }

        // Toggle Check All Checkboxes
        function toggleCheckAll(master) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = master.checked;
                const rowId = cb.closest('tr').id.replace('row_', '');
                toggleRowState(rowId, master.checked);
            });
        }

        // Disable/Enable Input Fields Based on Row Checkbox
        function toggleRowState(batchId, isEnabled) {
            const inputs = document.querySelectorAll(`.input-row-${batchId}`);
            inputs.forEach(input => {
                input.disabled = !isEnabled;
            });
        }

        // Calculate Realtime Per Row
        function calculateRowRealtime(batchId, source) {
            const freq = Math.max(1, parseValue(document.getElementById('master_freq').value));
            let perFeedG = parseValue(document.getElementById(`per_feed_${batchId}`).value);
            let totalKg = parseValue(document.getElementById(`total_kg_${batchId}`).value);

            if (source === 'per_feed') {
                if (perFeedG > 0) {
                    totalKg = (perFeedG * freq) / 1000;
                    document.getElementById(`total_kg_${batchId}`).value = formatDecimal(totalKg);
                }
            } else if (source === 'total_kg') {
                if (totalKg > 0 && freq > 0) {
                    perFeedG = (totalKg * 1000) / freq;
                    document.getElementById(`per_feed_${batchId}`).value = formatDecimal(perFeedG);
                }
            }
        }

        // Apply Master Values to All Checked Rows
        function applyMasterToAll() {
            const masterPerFeed = document.getElementById('master_per_feed').value;
            const checkboxes = document.querySelectorAll('.row-checkbox');

            if (!masterPerFeed || parseValue(masterPerFeed) <= 0) {
                alert('Silakan isi angka pada field "Pakan / Sesi Global (Gram)" terlebih dahulu!');
                return;
            }

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const batchId = cb.closest('tr').id.replace('row_', '');
                    document.getElementById(`per_feed_${batchId}`).value = masterPerFeed;
                    calculateRowRealtime(batchId, 'per_feed');
                }
            });
        }
    </script>
</x-layouts.app>