<x-layouts.app>
    <x-slot:title>Input Massal Kematian Ikan</x-slot:title>

    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
        .btn-back { background: #64748b; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: bold; text-decoration: none; }
        
        /* Master Control Box */
        .master-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .master-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; align-items: end; }

        /* Quick Fill Bar */
        .quick-fill-bar { background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .quick-fill-title { font-size: 0.8125rem; font-weight: bold; color: #991b1b; width: 100%; margin-bottom: 0.25rem; }

        /* Table Style */
        .table-container { background: white; border-radius: 8px; border: 1px solid #e2e8f0; overflow-x: auto; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .table { width: 100%; border-collapse: collapse; font-size: 0.875rem; text-align: left; }
        .table th { background: #f8fafc; padding: 12px; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .table tr.disabled-row { background: #f8fafc; opacity: 0.6; }

        .input-sm { width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.875rem; }
        .input-sm:focus { outline: none; border-color: #ef4444; }
        .btn-fill { background: #ef4444; color: white; padding: 6px 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 0.8125rem; white-space: nowrap; }
        .btn-fill:hover { background: #dc2626; }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">⚡ Input Massal Kematian Ikan (Mortality)</h1>
            <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">Pencatatan akumulasi ikan mati dan dugaan penyebab untuk semua kolam aktif secara bersamaan.</p>
        </div>
        <a href="{{ route('tenant.logs.mortality.index') }}" class="btn-back">← Kembali</a>
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

    <form action="{{ route('tenant.logs.mortality.bulk-store') }}" method="POST">
        @csrf

        <!-- MASTER CONTROL HEADER -->
        <div class="master-card">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #0f172a;">1. Tanggal Log Kematian</h3>
            <div class="master-grid">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Tanggal Log *</label>
                    <input type="date" name="log_date" value="{{ date('Y-m-d') }}" required class="input-sm" style="padding: 8px;">
                </div>
            </div>
        </div>

        <!-- QUICK FILL BAR -->
        <div class="quick-fill-bar">
            <div class="quick-fill-title">⚡ Isian Cepat (Salin Indikasi & Tindakan Serentak ke Kolam Tercentang)</div>
            <input type="text" id="fill_indication" placeholder="Indikasi (misal: Kanibalisme / Jamur)" class="input-sm" style="width: 250px;">
            <input type="text" id="fill_action" placeholder="Tindakan (misal: Garam krosok 2kg)" class="input-sm" style="width: 250px;">
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
                        <th style="width: 130px;">Jumlah Mati (Ekor) *</th>
                        <th style="width: 150px;">Total Bobot (Gram)</th>
                        <th>Dugaan Penyebab / Indikasi</th>
                        <th>Tindakan yang Diambil</th>
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
                                <input type="number" name="logs[{{ $index }}][quantity_pcs]" id="qty_{{ $batch->id }}" placeholder="0" min="1" class="input-sm val-qty" style="color: #dc2626; font-weight: bold;">
                            </td>
                            <td>
                                <input type="text" inputmode="decimal" name="logs[{{ $index }}][total_weight_g]" id="weight_{{ $batch->id }}" placeholder="Contoh: 1200" class="input-sm val-weight">
                            </td>
                            <td>
                                <input type="text" name="logs[{{ $index }}][indication]" id="indication_{{ $batch->id }}" placeholder="Kanibalisme / Air drop" class="input-sm val-indication">
                            </td>
                            <td>
                                <input type="text" name="logs[{{ $index }}][action_taken]" id="action_{{ $batch->id }}" placeholder="Siphon / Garam" class="input-sm val-action">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b; padding: 2rem;">
                                Tidak ada batch/siklus aktif saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activeBatches->count() > 0)
            <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('tenant.logs.mortality.index') }}" class="btn-back" style="background: #9ca3af;">Batal</a>
                <button type="submit" style="background: #ef4444; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.9375rem;">
                    💾 Simpan Semua Log Kematian
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
            const indication = document.getElementById('fill_indication').value;
            const action = document.getElementById('fill_action').value;

            document.querySelectorAll('.row-checkbox').forEach(cb => {
                if (cb.checked) {
                    const batchId = cb.closest('tr').id.replace('row_', '');
                    if (indication !== '') document.getElementById('indication_' + batchId).value = indication;
                    if (action !== '') document.getElementById('action_' + batchId).value = action;
                }
            });
        }
    </script>
</x-layouts.app>