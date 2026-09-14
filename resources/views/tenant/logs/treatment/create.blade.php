<x-layouts.app>
    <x-slot:title>Catat Treatment & Obat</x-slot:title>

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2>Form Treatment & Treatment Air</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/tenant/logs/treatment" method="POST">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Pilih Siklus / Kolam Aktif *</label>
                <select name="batch_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Pilih Kolam Aktif --</option>
                    @foreach($activeBatches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->pond->name ?? '-' }} ({{ $batch->batch_code }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tanggal Treatment *</label>
                <input type="date" name="treatment_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Nama Bahan / Produk *</label>
                <input type="text" name="product_name" value="{{ old('product_name') }}" required placeholder="Contoh: Kapur Dolomit, Probiotik A, Garam Krosok" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Jumlah Dosis *</label>
                    <input type="text" inputmode="decimal" name="dosage_amount" value="{{ old('dosage_amount') }}" required placeholder="Contoh: 2,5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Satuan *</label>
                    <select name="dosage_unit" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="Kg">Kg</option>
                        <option value="Gram">Gram</option>
                        <option value="Liter">Liter</option>
                        <option value="ml">ml</option>
                        <option value="ppm">ppm</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tujuan Treatment (Opsional)</label>
                <input type="text" name="purpose" value="{{ old('purpose') }}" placeholder="Contoh: Menaikkan pH air, Sterilisasi air, Menumbuhkan bioflok" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Treatment</button>
                <a href="/tenant/logs/treatment" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>