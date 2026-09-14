<x-layouts.app>
    <x-slot:title>Catat Panen Baru</x-slot:title>

    <div style="max-width: 650px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2>Form Catatan Panen</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/tenant/harvests" method="POST">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Pilih Siklus / Kolam Aktif *</label>
                <select name="batch_id" id="batch_id" onchange="calculateHarvestRealtime()" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="" data-population="0">-- Pilih Kolam Aktif --</option>
                    @foreach($activeBatches as $batch)
                        @php
                            $totalMati = $batch->mortality_log_sum_quantity_pcs ?? 0;
                            $totalParsial = $batch->harvest_log_sum_total_pcs ?? 0;
                            $sisaPopulasi = max(0, $batch->initial_seed_count - $totalMati - $totalParsial);
                        @endphp
                        <option value="{{ $batch->id }}" data-population="{{ $sisaPopulasi }}">
                            {{ $batch->pond->name ?? '-' }} ({{ $batch->batch_code }}) - Pop Sisa: {{ number_format($sisaPopulasi) }} ekor
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tanggal Panen *</label>
                    <input type="date" name="harvest_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tipe Panen *</label>
                    <select name="harvest_type" id="harvest_type" onchange="calculateHarvestRealtime()" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="partial">Panen Parsial (Sebagian)</option>
                        <option value="total">Panen Total (Tutup Siklus)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Berat Total Panen (Kg) *</label>
                    <input type="text" inputmode="decimal" id="weight_kg" name="weight_kg" value="{{ old('weight_kg') }}" oninput="calculateHarvestRealtime()" required placeholder="Contoh: 250,5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Harga per Kg (Rp)</label>
                    <input type="text" inputmode="decimal" id="price_per_kg" name="price_per_kg" value="{{ old('price_per_kg') }}" oninput="calculateHarvestRealtime()" placeholder="Contoh: 24000" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Ukuran Ikan / Size (Ekor/Kg)</label>
                    <input type="number" id="fish_size" name="fish_size" value="{{ old('fish_size') }}" oninput="calculateHarvestRealtime()" placeholder="Contoh: 10" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Estimasi Ekor (Otomatis)</label>
                    {{-- Input dibuat READONLY agar tidak mengunci saat edit Size --}}
                    <input type="text" id="display_total_pcs" readonly placeholder="Hasil (Size x Kg)" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background: #f3f4f6; color: #374151; font-weight: bold;">
                    <input type="hidden" id="total_pcs" name="total_pcs" value="{{ old('total_pcs') }}">
                </div>
            </div>

            {{-- Widget Preview Real-time --}}
            <div id="harvest_preview" style="display: none; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                <div style="font-size: 0.85rem; color: #065f46; font-weight: bold; margin-bottom: 6px;">Ringkasan Hasil Panen (Real-time):</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; font-size: 0.9rem; color: #047857;">
                    <div>Total Omzet: <strong id="prev_revenue" style="color: #059669; font-size: 1.05rem;">Rp 0</strong></div>
                    <div>Est. Terpanen: <strong id="prev_pcs">0</strong> ekor</div>
                    <div>Rata-rata Bobot: <strong id="prev_mbw">0</strong> g/ekor</div>
                    <div id="prev_remaining_wrapper">Sisa Pop. Kolam: <strong id="prev_remaining">0</strong> ekor</div>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Nama Pembeli / Tengkulak</label>
                <input type="text" name="buyer_name" value="{{ old('buyer_name') }}" placeholder="Contoh: Pak Haji Ahmad" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Catatan / Keterangan (Opsional)</label>
                <textarea name="notes" rows="2" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Catatan kondisi ikan atau pemotongan timbangan..."></textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Catatan Panen</button>
                <a href="/tenant/harvests" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Batal</a>
            </div>
        </form>
    </div>

    <script>
        function parseVal(id) {
            const el = document.getElementById(id);
            if (!el || !el.value) return 0;
            return parseFloat(el.value.toString().replace(',', '.')) || 0;
        }

        function calculateHarvestRealtime() {
            const batchSelect = document.getElementById('batch_id');
            const selectedOption = batchSelect.options[batchSelect.selectedIndex];
            const currentPop = parseFloat(selectedOption ? selectedOption.getAttribute('data-population') : 0) || 0;

            const weightKg = parseVal('weight_kg');
            const pricePerKg = parseVal('price_per_kg');
            const fishSize = parseVal('fish_size');

            // 1. Hitung Revenue
            const revenue = weightKg * pricePerKg;
            
            // 2. Hitung Total Ekor Selalu Dinamis dari (Berat Kg x Size)
            let calculatedPcs = 0;
            if (weightKg > 0 && fishSize > 0) {
                calculatedPcs = Math.round(weightKg * fishSize);
            }

            // Update Field Display dan Hidden Input
            document.getElementById('display_total_pcs').value = calculatedPcs > 0 ? calculatedPcs.toLocaleString('id-ID') + ' ekor' : '';
            document.getElementById('total_pcs').value = calculatedPcs > 0 ? calculatedPcs : '';

            // 3. Hitung MBW (Gram/Ekor)
            const mbw = fishSize > 0 ? (1000 / fishSize) : 0;

            // 4. Hitung Sisa Populasi
            const remaining = Math.max(0, currentPop - calculatedPcs);

            // Update Widget UI
            const previewBox = document.getElementById('harvest_preview');
            if (weightKg > 0 || pricePerKg > 0) {
                previewBox.style.display = 'block';
                
                document.getElementById('prev_revenue').innerText = 'Rp ' + revenue.toLocaleString('id-ID');
                document.getElementById('prev_pcs').innerText = calculatedPcs.toLocaleString('id-ID');
                document.getElementById('prev_mbw').innerText = mbw.toFixed(1).replace('.', ',');
                
                const harvestType = document.getElementById('harvest_type').value;
                const remainingWrapper = document.getElementById('prev_remaining_wrapper');
                
                if (harvestType === 'total') {
                    remainingWrapper.style.display = 'none';
                } else {
                    remainingWrapper.style.display = 'block';
                    document.getElementById('prev_remaining').innerText = remaining.toLocaleString('id-ID');
                }
            } else {
                previewBox.style.display = 'none';
            }
        }
    </script>
</x-layouts.app>