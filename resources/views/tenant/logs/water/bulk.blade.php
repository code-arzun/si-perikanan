<x-layouts.app>
    <x-slot:title>Input Massal Kualitas Air</x-slot:title>

    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
        .btn-back { background: #64748b; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: bold; text-decoration: none; }
        
        /* Master Control Box */
        .master-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .master-grid { display: grid; grid-template-columns: 1fr 1fr 2fr; gap: 1rem; align-items: end; }

        /* Quick Fill Bar */
        .quick-fill-bar { background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .quick-fill-title { font-size: 0.8125rem; font-weight: bold; color: #0369a1; width: 100%; margin-bottom: 0.25rem; }

        /* Table Style */
        .table-container { background: white; border-radius: 8px; border: 1px solid #e2e8f0; overflow-x: auto; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .table { width: 100%; border-collapse: collapse; font-size: 0.875rem; text-align: left; }
        .table th { background: #f8fafc; padding: 12px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .table tr.disabled-row { background: #f8fafc; opacity: 0.6; }

        .input-sm { width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem; }
        .input-sm:focus { outline: none; border-color: #2563eb; }
        .btn-fill { background: #0284c7; color: white; padding: 6px 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 0.8125rem; white-space: nowrap; }
        .btn-fill:hover { background: #0369a1; }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">⚡ Input Massal Kualitas Air</h1>
            <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">Catat parameter fisik & kimia air untuk semua kolam aktif secara bersamaan.</p>
        </div>
        <a href="{{ route('tenant.logs.water.index') }}" class="btn-back">← Kembali</a>
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

    <form action="{{ route('tenant.logs.water.bulk-store') }}" method="POST">
        @csrf

        <!-- MASTER CONTROL HEADER -->
        <div class="master-card">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #0f172a;">1. Informasi Pengukuran Utama</h3>
            <div class="master-grid">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Tanggal Cek *</label>
                    <input type="date" name="check_date" value="{{ date('Y-m-d') }}" required class="input-sm" style="padding: 8px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Sesi Pengecekan *</label>
                    <select name="check_time_session" required class="input-sm" style="padding: 8px;">
                        <option value="pagi" selected>Pagi</option>
                        <option value="siang">Siang</option>
                        <option value="sore">Sore</option>
                        <option value="malam">Malam</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- QUICK FILL BAR -->
        <div class="quick-fill-bar">
            <div class="quick-fill-title">⚡ Isian Cepat (Salin Parameter Serentak ke Kolam Tercentang)</div>
            <input type="text" id="fill_ph" placeholder="pH (misal: 7,5)" class="input-sm" style="width: 110px;">
            <input type="text" id="fill_do" placeholder="DO (misal: 5,2)" class="input-sm" style="width: 110px;">
            <input type="text" id="fill_temp" placeholder="Suhu °C (28)" class="input-sm" style="width: 110px;">
            <input type="text" id="fill_trans" placeholder="Kecerahan cm" class="input-sm" style="width: 120px;">
            <input type="text" id="fill_color" placeholder="Warna Air" class="input-sm" style="width: 130px;">
            <button type="button" onclick="applyQuickFill()" class="btn-fill">Terapkan ke Semua</button>
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
                        <th style="width: 110px;">pH</th>
                        <th style="width: 120px;">DO (mg/L)</th>
                        <th style="width: 110px;">Suhu (°C)</th>
                        <th style="width: 130px;">Kecerahan (cm)</th>
                        <th style="width: 150px;">Warna Air</th>
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
                                <input type="text" inputmode="decimal" name="logs[{{ $index }}][ph]" id="ph_{{ $batch->id }}" placeholder="7,5" class="input-sm val-ph">
                            </td>
                            <td>
                                <input type="text" inputmode="decimal" name="logs[{{ $index }}][do_mg_l]" id="do_{{ $batch->id }}" placeholder="5,0" class="input-sm val-do">
                            </td>
                            <td>
                                <input type="text" inputmode="decimal" name="logs[{{ $index }}][temperature_c]" id="temp_{{ $batch->id }}" placeholder="28,0" class="input-sm val-temp">
                            </td>
                            <td>
                                <input type="text" inputmode="decimal" name="logs[{{ $index }}][transparency_cm]" id="trans_{{ $batch->id }}" placeholder="35" class="input-sm val-trans">
                            </td>
                            <td>
                                <input type="text" name="logs[{{ $index }}][water_color]" id="color_{{ $batch->id }}" placeholder="Hijau Muda" class="input-sm val-color">
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
                <a href="{{ route('tenant.logs.water.index') }}" class="btn-back" style="background: #9ca3af;">Batal</a>
                <button type="submit" style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.9375rem;">
                    💾 Simpan Semua Log Kualitas Air
                </button>
            </div>
        @endif
    </form>

    <!-- JAVASCRIPT LOGIC -->
    <script>
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

        // Quick Fill Application
        function applyQuickFill() {
            const ph = document.getElementById('fill_ph').value;
            const doVal = document.getElementById('fill_do').value;
            const temp = document.getElementById('fill_temp').value;
            const trans = document.getElementById('fill_trans').value;
            const color = document.getElementById('fill_color').value;

            document.querySelectorAll('.row-checkbox').forEach(cb => {
                if (cb.checked) {
                    const batchId = cb.closest('tr').id.replace('row_', '');
                    if (ph !== '') document.getElementById('ph_' + batchId).value = ph;
                    if (doVal !== '') document.getElementById('do_' + batchId).value = doVal;
                    if (temp !== '') document.getElementById('temp_' + batchId).value = temp;
                    if (trans !== '') document.getElementById('trans_' + batchId).value = trans;
                    if (color !== '') document.getElementById('color_' + batchId).value = color;
                }
            });
        }
    </script>
</x-layouts.app>