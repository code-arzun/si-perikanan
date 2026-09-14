<x-layouts.app>
    <x-slot:title>Catat Kematian Ikan</x-slot:title>

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2>Form Catat Kematian Ikan</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/tenant/logs/mortality" method="POST">
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
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tanggal Kejadian / Log *</label>
                <input type="date" name="log_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Jumlah Mati (Ekor) *</label>
                    <input type="number" name="quantity_pcs" value="{{ old('quantity_pcs') }}" required placeholder="Contoh: 15" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Total Bobot Mati (Kg)</label>
                    <input type="text" inputmode="decimal" name="total_weight_kg" value="{{ old('total_weight_kg') }}" placeholder="Contoh: 0,8" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Dugaan Penyebab / Indikasi</label>
                <input type="text" name="indication" value="{{ old('indication') }}" placeholder="Contoh: Kanibalisme, serangan jamur, perubahan suhu drastis" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tindakan Penanganan</label>
                <textarea name="action_taken" rows="3" placeholder="Contoh: Dilakukan penggaraman 1kg/m3 dan kuras air 20%" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #ef4444; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Data Kematian</button>
                <a href="/tenant/logs/mortality" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>