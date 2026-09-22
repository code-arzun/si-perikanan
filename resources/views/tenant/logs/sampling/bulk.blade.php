<x-layouts.app>
    <x-slot:title>Input Massal Sampling Pertumbuhan</x-slot:title>

    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
        .btn-back { background: #64748b; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: bold; text-decoration: none; }
        
        /* Master Control Box */
        .master-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .master-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; align-items: end; }

        /* Quick Fill Bar */
        .quick-fill-bar { background: #eff6ff; border: 1px solid #93c5fd; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .quick-fill-title { font-size: 0.8125rem; font-weight: bold; color: #1e40af; width: 100%; margin-bottom: 0.25rem; }

        /* Table Style */
        .table-container { background: white; border-radius: 8px; border: 1px solid #e2e8f0; overflow-x: auto; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .table { width: 100%; border-collapse: collapse; font-size: 0.875rem; text-align: left; }
        .table th { background: #f8fafc; padding: 12px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .table tr.disabled-row { background: #f8fafc; opacity: 0.6; }

        .input-sm { width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem; }
        .input-sm:focus { outline: none; border-color: #2563eb; }
        .btn-fill { background: #2563eb; color: white; padding: 6px 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 0.8125rem; white-space: nowrap; }
        .btn-fill:hover { background: #1d4ed8; }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">⚡ Input Massal Sampling Pertumbuhan (MBW)</h1>
            <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">Catat bobot rata-rata, panjang, dan estimasi biomassa untuk semua kolam aktif secara bersamaan.</p>
        </div>
        <a href="{{ route('tenant.logs.sampling.index') }}" class="btn-back">← Kembali</a>
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

    <form action="{{ route('tenant.logs.sampling.bulk-store') }}" method="POST">
        @csrf

        <!-- MASTER CONTROL HEADER -->
        <div class="master-card">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #0f172a;">1. Tanggal Sampling Utama</h3>
            <div class="master-grid">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Tanggal Sampling *</label>
                    <input type="date" name="sampling_date" value="{{ date('Y-m-d') }}" required class="input-sm" style="padding: 8px;">
                </div>
            </div>
        </div>

        <!-- QUICK FILL BAR -->
        <div class="quick-fill-bar">
            <div class="quick-fill-title">⚡ Isian Cepat (Salin Jumlah Sampel & Hitung MBW Otomatis ke Kolam Tercentang)</div>
            <input type="number" id="fill_sample_pcs" placeholder="Jml Sampel (Ekor)" class="input-sm" style="width: 150px;">
            <input type="text" inputmode="decimal" id="fill_total_weight_g" placeholder="Total Bobot (Gram)" class="input-sm" style="width: 160px;">
            <input type="text" inputmode="decimal" id="fill_avg_length" placeholder="Panjang (cm)" class="input-sm" style="width: 130px;">
            <button type="button" onclick="applyQuickFill()" class="btn-fill">Terapkan & Hitung MBW</button>
        </div>

        <!-- TABLE MATRIX LOGS -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="check_all" checked onclick="toggleCheckAll(this)">
                        </th>
                        <th>Kolam / Batch</th>
                        <th style="width: 130px;">Jumlah Sampel (Ekor) *</th>
                        <th style="width: 140px; background: #f1f5f9;">Total Bobot Sampel (Gram)</th>
                        <th style="width: 140px;">MBW / Rata-rata (Gram) *</th>
                        <th style="width: 130px;">Panjang Rata-rata (cm)</th>
                        <th style="width: 140px;">Estimasi Biomassa (Kg)</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeBatches as $index => $batch)
                        <tr id="row_{{ $batch->id }}">
                            <td style="text-align: center;">
                                <input type="checkbox" class="row-checkbox" checked onchange="toggleRowDisabled({{ $batch->id }}, this.checked)">
                                <input type="hidden" name="logs[{{ $index }}][batch_id]" value="{{ $batch->id }}" id="batch_id_{{ $batch->id }}">
                            </td>
                            <td>
                                <strong>{{ $batch->pond->name ?? 'Kolam' }}</strong><br>
                                <small style="color: #2563eb;">{{ $batch->batch_code }}</small>
                            </td>
                            <td>
                                <input type="number" name="logs[{{ $index }}][sample_count_pcs]" id="pcs_{{ $batch->id }}" placeholder="50" min="1" oninput="calculateRowMBW({{ $batch->id }})" class="input-sm val-pcs" style="font-weight: bold;">
                            </td>
                            <td style="background: #fafafa;">
                                <input type="text" inputmode="decimal" id="total_g_{{ $batch->id }}" placeholder="2500" oninput="calculateRowMBW({{ $batch->id }})" class="input-sm val-total-g">
                            </td>
                            <td>
                                <input type="text" inputmode="decimal" name="logs[{{ $index }}][avg_weight_g]" id="mbw_{{ $batch->id }}" placeholder="50,00" class="input-sm val-mbw" style="color: #2563eb; font-weight: bold;">
                            </td>
                            <td>
                                <input type="text" inputmode="decimal" name="logs[{{ $index }}][avg_length_cm]" id="length_{{ $batch->id }}" placeholder="12,5" class="input-sm val-length">
                            </td>
                            <td>
                                <input type="text" inputmode="decimal" name="logs[{{ $index }}][estimated_biomass_kg]" id="biomass_{{ $batch->id }}" placeholder="250" class="input-sm val-biomass">
                            </td>
                            <td>
                                <input type="text" name="logs[{{ $index }}][notes]" id="notes_{{ $batch->id }}" placeholder="Opsional" class="input-sm">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: #64748b; padding: 2rem;">
                                Tidak ada batch/siklus aktif saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activeBatches->count() > 0)
            <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('tenant.logs.sampling.index') }}" class="btn-back" style="background: #9ca3af;">Batal</a>
                <button type="submit" style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.9375rem;">
                    💾 Simpan Semua Log Sampling
                </button>
            </div>
        @endif
    </form>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Helper Parser
        function parseValue(val) {
            if (!val) return 0;
            return parseFloat(val.toString().replace(',', '.')) || 0;
        }

        function formatDecimal(num) {
            return num.toFixed(2).replace('.', ',');
        }

        // Check All Checkbox
        function toggleCheckAll(master) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = master.checked;
                const batchId = cb.closest('tr').id.replace('row_', '');
                toggleRowDisabled(batchId, master.checked);
            });
        }

        // Toggle Row Input State
        function toggleRowDisabled(batchId, isChecked) {
            const row = document.getElementById('row_' + batchId);
            const inputs = row.querySelectorAll('input:not(.row-checkbox)');

            if (isChecked) {
                row.classList.remove('disabled-row');
                inputs.forEach(input => input.removeAttribute('disabled'));
            } else {
                row.classList.add('disabled-row');
                inputs.forEach(input => input.setAttribute('disabled', 'disabled'));
            }
        }

        // Realtime MBW Calculator per Row
        function calculateRowMBW(batchId) {
            const pcs = parseValue(document.getElementById('pcs_' + batchId).value);
            const totalG = parseValue(document.getElementById('total_g_' + batchId).value);

            if (pcs > 0 && totalG > 0) {
                const mbw = totalG / pcs;
                document.getElementById('mbw_' + batchId).value = formatDecimal(mbw);
            }
        }

        // Quick Fill Application
        function applyQuickFill() {
            const pcs = document.getElementById('fill_sample_pcs').value;
            const totalG = document.getElementById('fill_total_weight_g').value;
            const length = document.getElementById('fill_avg_length').value;

            document.querySelectorAll('.row-checkbox').forEach(cb => {
                if (cb.checked) {
                    const batchId = cb.closest('tr').id.replace('row_', '');
                    
                    if (pcs !== '') document.getElementById('pcs_' + batchId).value = pcs;
                    if (totalG !== '') document.getElementById('total_g_' + batchId).value = totalG;
                    if (length !== '') document.getElementById('length_' + batchId).value = length;

                    // Hitung Ulang MBW Baris Ini
                    calculateRowMBW(batchId);
                }
            });
        }
    </script>
</x-layouts.app>