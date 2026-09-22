<x-layouts.app>
    <x-slot:title>Log Treatment & Obat</x-slot:title>

    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
        .btn-add { background: #059669; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: bold; text-decoration: none; cursor: pointer; border: none; }
        .btn-add:hover { background: #047857; }

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
        .stat-value { font-size: 1rem; font-weight: 700; color: #0f172a; }

        /* Targeted Add Button */
        .btn-add-sm { background: #059669; color: white; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.8125rem; font-weight: 600; border: none; cursor: pointer; white-space: nowrap; }
        .btn-add-sm:hover { background: #047857; }

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

        /* MODAL STYLES WITH BACKDROP LOCK */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(3px); z-index: 1000; justify-content: center; align-items: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-card { background: white; width: 100%; max-width: 580px; border-radius: 10px; padding: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-height: 90vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e2e8f0; }
        .btn-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b; }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Catatan Treatment, Kapur & Probiotik</h1>
            <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">Pencatatan pemberian bahan kimia, kapur, garam, probiotik, dan obat terkelompok per batch/siklus aktif.</p>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('tenant.logs.treatment.bulk-create') }}" style="background: #0284c7; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: bold; text-decoration: none;">
                ⚡ Input Massal
            </a>
        </div>
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
                            <div class="stat-label">Total Treatment</div>
                            <div class="stat-value" style="color: #059669;">
                                {{ number_format($batch->total_treatments, 0, ',', '.') }} Kali
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Treatment Terakhir</div>
                            <div class="stat-value" style="color: #0284c7; font-size: 0.875rem;">
                                {{ $batch->latest_log ? $batch->latest_log->product_name : '-' }}
                            </div>
                        </div>

                        <!-- Tombol Target Batch -->
                        <button type="button" class="btn-add-sm" 
                                onclick="event.stopPropagation(); openTreatmentModal({{ $batch->id }}, '{{ $batch->pond->name ?? 'Kolam' }}', '{{ $batch->batch_code }}')" 
                                title="Catat Treatment untuk {{ $batch->pond->name ?? 'Kolam ini' }}">
                            + Catat
                        </button>

                        <div class="toggle-icon">▼</div>
                    </div>
                </div>

                <!-- Card Content (Tabel Riwayat) -->
                <div class="batch-content">
                    <div style="margin-bottom: 0.75rem; font-size: 0.8125rem; color: #64748b; display: flex; justify-content: space-between; align-items: center;">
                        <span>📋 <strong>Riwayat Treatment Siklus {{ $batch->batch_code }}</strong></span>
                        @if($batch->latest_log)
                            <span>Pemberian Terakhir: <strong>{{ \Carbon\Carbon::parse($batch->latest_log->treatment_date)->format('d/m/Y') }}</strong></span>
                        @endif
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Bahan / Produk</th>
                                <th style="text-align: right;">Dosis</th>
                                <th>Tujuan Treatment</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batch->treatmentLog as $log)
                                <tr>
                                    <td><strong>{{ \Carbon\Carbon::parse($log->treatment_date)->format('d M Y') }}</strong></td>
                                    <td><strong>{{ $log->product_name }}</strong></td>
                                    <td style="text-align: right; font-weight: bold; color: #059669;">
                                        {{ number_format($log->dosage_amount, 2, ',', '.') }} {{ $log->dosage_unit }}
                                    </td>
                                    <td>{{ $log->purpose ?? '-' }}</td>
                                    <td>{{ $log->user->name ?? 'Sistem' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #64748b; padding: 1.5rem;">
                                        Belum ada catatan treatment air/kolam pada batch ini.
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

    <!-- MODAL POP-UP FORM TREATMENT -->
    <div id="treatmentModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3 style="margin: 0; font-size: 1.125rem; font-weight: bold; color: #0f172a;">Form Input Treatment & Obat</h3>
                <button type="button" class="btn-close" onclick="closeTreatmentModal()">&times;</button>
            </div>

            <form action="{{ route('tenant.logs.treatment.store') }}" method="POST">
                @csrf

                <!-- 1 ELEMEN UTAMA BATCH ID (Hidden) -->
                <input type="hidden" name="batch_id" id="modal_batch_id_hidden">

                <!-- Container Dynamic Batch Select / Locked Text -->
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Siklus / Kolam Aktif *</label>
                    
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

                    <div id="wrapper_locked_batch" style="display: none;">
                        <input type="text" id="modal_pond_display" readonly style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; background: #f1f5f9; color: #475569; font-weight: bold; cursor: not-allowed;">
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Tanggal Treatment *</label>
                    <input type="date" name="treatment_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Nama Bahan / Produk *</label>
                    <input type="text" name="product_name" placeholder="Contoh: Kapur Tohor / Molase / Probiotik A" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Dosis / Jumlah *</label>
                        <input type="text" inputmode="decimal" name="dosage_amount" placeholder="Contoh: 5" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Satuan *</label>
                        <select name="dosage_unit" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            <option value="Kg" selected>Kg</option>
                            <option value="Gram">Gram</option>
                            <option value="Liter">Liter</option>
                            <option value="ml">ml</option>
                            <option value="ppm">ppm</option>
                            <option value="Sachet">Sachet</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 4px; font-weight: bold; font-size: 0.875rem;">Tujuan Treatment</label>
                    <textarea name="purpose" rows="2" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Contoh: Menaikkan pH / Penumbuhan plankton / Sterilisasi..."></textarea>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeTreatmentModal()" style="background: #9ca3af; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Batal</button>
                    <button type="submit" style="background: #059669; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Log</button>
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
        function openTreatmentModal(batchId = null, pondName = '', batchCode = '') {
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
                // Klik dari Tombol Utama Header
                lockedWrapper.style.display = 'none';
                selectWrapper.style.display = 'block';
                
                selectBatchInput.value = '';
                hiddenBatchInput.value = '';
            }

            document.getElementById('treatmentModal').classList.add('active');
        }

        // Close Modal Function
        function closeTreatmentModal() {
            document.getElementById('treatmentModal').classList.remove('active');
        }
    </script>
</x-layouts.app>