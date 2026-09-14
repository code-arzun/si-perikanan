<x-layouts.app>
    <x-slot:title>Catat Sampling Pertumbuhan</x-slot:title>

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2>Form Sampling Pertumbuhan</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/tenant/logs/sampling" method="POST">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Pilih Siklus / Kolam Aktif *</label>
                <select name="batch_id" id="batch_id" onchange="calculateRealtime()" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="" data-population="0">-- Pilih Kolam Aktif --</option>
                    @foreach($activeBatches as $batch)
                        <option value="{{ $batch->id }}" data-population="{{ $batch->current_population }}">
                            {{ $batch->pond->name ?? '-' }} ({{ $batch->batch_code }}) - Est. Populasi: {{ number_format($batch->current_population) }} ekor
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tanggal Sampling *</label>
                    <input type="date" name="sampling_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Jumlah Sample (Ekor) *</label>
                    <input type="number" id="sample_count_pcs" name="sample_count_pcs" value="{{ old('sample_count_pcs', 30) }}" oninput="calculateRealtime()" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Total Berat Sampel (Gram)</label>
                    <input type="text" inputmode="decimal" id="sample_total_weight_g" placeholder="Contoh: 450" oninput="calculateRealtime()" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <small style="color: #6b7280; font-size: 11px;">Isi ini jika menimbang sekelompok sampel</small>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">MBW / Rata-rata Bobot (Gram) *</label>
                    <input type="text" inputmode="decimal" id="avg_weight_g" name="avg_weight_g" value="{{ old('avg_weight_g') }}" oninput="calculateRealtime('mbw')" required placeholder="Contoh: 15" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            {{-- Widget Hasil Perhitungan Realtime --}}
            <div id="realtime_preview" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <div style="font-size: 0.85rem; color: #1e40af; font-weight: bold;">Hasil Perhitungan Otomatis:</div>
                <div style="display: flex; gap: 1.5rem; margin-top: 4px; font-size: 0.9rem; color: #1e3a8a;">
                    <div>MBW: <strong id="preview_mbw">0</strong> Gram/ekor</div>
                    <div>Est. Biomasa: <strong id="preview_biomass">0</strong> Kg</div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Panjang Rata-rata (cm)</label>
                    <input type="text" inputmode="decimal" name="avg_length_cm" value="{{ old('avg_length_cm') }}" placeholder="Contoh: 10,2" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Estimasi Biomasa Kolam (Kg)</label>
                    <input type="text" inputmode="decimal" id="estimated_biomass_kg" name="estimated_biomass_kg" value="{{ old('estimated_biomass_kg') }}" placeholder="Otomatis dari kalkulasi" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Sampling</button>
                <a href="/tenant/logs/sampling" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Batal</a>
            </div>
        </form>
    </div>

    <script>
        function parseValue(val) {
            if (!val) return 0;
            return parseFloat(val.toString().replace(',', '.')) || 0;
        }

        function calculateRealtime(source = 'total_weight') {
            const batchSelect = document.getElementById('batch_id');
            const selectedOption = batchSelect.options[batchSelect.selectedIndex];
            const population = parseValue(selectedOption ? selectedOption.getAttribute('data-population') : 0);

            const sampleCount = parseValue(document.getElementById('sample_count_pcs').value);
            const totalWeight = parseValue(document.getElementById('sample_total_weight_g').value);
            let mbw = parseValue(document.getElementById('avg_weight_g').value);

            // Jika memasukkan total berat sampel dan jumlah ekor
            if (source === 'total_weight' && totalWeight > 0 && sampleCount > 0) {
                mbw = totalWeight / sampleCount;
                document.getElementById('avg_weight_g').value = mbw.toFixed(2).replace('.', ',');
            }

            // Hitung Biomasa (Kg) = (Populasi * MBW) / 1000
            let biomass = 0;
            if (population > 0 && mbw > 0) {
                biomass = (population * mbw) / 1000;
                document.getElementById('estimated_biomass_kg').value = biomass.toFixed(2).replace('.', ',');
            }

            // Tampilkan Widget Preview
            const previewBox = document.getElementById('realtime_preview');
            if (mbw > 0) {
                previewBox.style.display = 'block';
                document.getElementById('preview_mbw').innerText = mbw.toFixed(2).replace('.', ',');
                document.getElementById('preview_biomass').innerText = biomass.toFixed(2).replace('.', ',');
            } else {
                previewBox.style.display = 'none';
            }
        }
    </script>
</x-layouts.app>