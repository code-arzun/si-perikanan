<x-layouts.app>
    <x-slot:title>Catat Pakan Harian</x-slot:title>

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2>Form Catat Pakan Harian</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/tenant/logs/feed" method="POST">
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

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tanggal Pemberian *</label>
                    <input type="date" name="feed_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Waktu / Jam *</label>
                    <input type="time" name="feed_time" value="{{ date('H:i') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Jumlah Pakan (Kg) *</label>
                    <input type="text" inputmode="decimal" name="amount_kg" value="{{ old('amount_kg') }}" required placeholder="Contoh: 2,5 atau 2.5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Jenis Pakan (Opsional)</label>
                    <select name="feed_type_id" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">-- Pilih Jenis Pakan --</option>
                        @foreach($feedTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->brand }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Respon Nafsu Makan *</label>
                <select name="appetite_response" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="sangat_baik">Sangat Baik (Lalap Habis)</option>
                    <option value="baik" selected>Baik (Normal)</option>
                    <option value="kurang">Kurang (Masih Sisa)</option>
                    <option value="buruk">Buruk (Pakan Lambat Disambar)</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Catatan / Keterangan</label>
                <textarea name="notes" rows="3" placeholder="Contoh: Weather hujan, pakan dikurangi 10%" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Catatan Pakan</button>
                <a href="/tenant/logs/feed" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>