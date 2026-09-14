<x-layouts.app>
    <x-slot:title>Mulai Siklus Budidaya Baru</x-slot:title>

    <div style="max-width: 650px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2>Form Tebar Bibit (Siklus Baru)</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/tenant/batches" method="POST">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Kode Siklus / Batch *</label>
                <input type="text" name="batch_code" value="{{ old('batch_code', 'BATCH-'.date('Ymd').'-01') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Pilih Kolam (Kosong) *</label>
                    <select name="pond_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">-- Pilih Kolam --</option>
                        @foreach($ponds as $pond)
                            <option value="{{ $pond->id }}">{{ $pond->name }} ({{ $pond->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Komoditas Ikan *</label>
                    <select name="fish_species_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">-- Pilih Spesies Ikan --</option>
                        @foreach($fishSpecies as $species)
                            <option value="{{ $species->id }}">{{ $species->name }} ({{ $species->latin_name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tanggal Tebar Bibit *</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" onchange="updateEstimatedHarvestDate()" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Perkiraan Panen</label>
                    <input type="date" id="estimated_harvest_date" name="estimated_harvest_date" value="{{ old('estimated_harvest_date') }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            {{-- Opsi Cepat & Kustom Durasi Panen --}}
            <div style="background: #f9fafb; padding: 0.75rem; border-radius: 6px; border: 1px solid #e5e7eb; margin-bottom: 1rem;">
                <span style="font-size: 0.85rem; font-weight: bold; color: #4b5563; display: block; margin-bottom: 6px;">Hitung Otomatis Masa Pemeliharaan:</span>
                <div style="display: flex; flex-wrap: wrap; gap: 6px; align-items: center;">
                    <button type="button" onclick="setHarvestDays(60)" style="background: #e0e7ff; color: #4338ca; border: none; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; cursor: pointer;">+60 Hari</button>
                    <button type="button" onclick="setHarvestDays(90)" style="background: #e0e7ff; color: #4338ca; border: none; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; cursor: pointer;">+90 Hari</button>
                    <button type="button" onclick="setHarvestDays(120)" style="background: #e0e7ff; color: #4338ca; border: none; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; cursor: pointer;">+120 Hari</button>
                    
                    <div style="display: flex; align-items: center; gap: 4px; margin-left: 6px;">
                        <input type="number" id="custom_days" placeholder="Lainnya" oninput="setHarvestDays(this.value)" style="width: 75px; padding: 3px 6px; font-size: 0.8rem; border: 1px solid #ccc; border-radius: 4px;">
                        <span style="font-size: 0.8rem; color: #6b7280;">Hari</span>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Jumlah Tebar (Ekor) *</label>
                    <input type="number" name="initial_seed_count" value="{{ old('initial_seed_count') }}" required placeholder="Contoh: 5000" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Bobot Rata-rata Awal (Gram) *</label>
                    <input type="text" inputmode="decimal" name="initial_avg_weight_g" value="{{ old('initial_avg_weight_g') }}" required placeholder="Contoh: 2,5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Harga Bibit per Ekor (Rp) (Opsional)</label>
                <input type="text" inputmode="decimal" name="seed_price_per_unit" value="{{ old('seed_price_per_unit') }}" placeholder="Contoh: 150 (Boleh dikosongkan jika 0)" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Mulai Siklus Budidaya</button>
                <a href="/tenant/batches" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Batal</a>
            </div>
        </form>
    </div>

    <script>
        let selectedDays = 0;

        function setHarvestDays(days) {
            days = parseInt(days);
            if (isNaN(days) || days <= 0) return;
            
            selectedDays = days;
            document.getElementById('custom_days').value = days;
            updateEstimatedHarvestDate();
        }

        function updateEstimatedHarvestDate() {
            const startDateInput = document.getElementById('start_date').value;
            if (!startDateInput || selectedDays <= 0) return;

            const startDate = new Date(startDateInput);
            startDate.setDate(startDate.getDate() + selectedDays);

            // Format YYYY-MM-DD untuk input HTML date
            const year = startDate.getFullYear();
            const month = String(startDate.getMonth() + 1).padStart(2, '0');
            const day = String(startDate.getDate()).padStart(2, '0');

            document.getElementById('estimated_harvest_date').value = `${year}-${month}-${day}`;
        }
    </script>
</x-layouts.app>