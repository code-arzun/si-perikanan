<x-layouts.app>
    <x-slot:title>Catat Kualitas Air</x-slot:title>

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2>Form Pengecekan Kualitas Air</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/tenant/logs/water" method="POST">
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
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tanggal Cek *</label>
                    <input type="date" name="check_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Sesi Waktu *</label>
                    <select name="check_time_session" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="pagi">Pagi Hari</option>
                        <option value="siang">Siang Hari</option>
                        <option value="sore">Sore Hari</option>
                        <option value="malam">Malam Hari</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">pH Air (Keasaman)</label>
                    <input type="text" inputmode="decimal" name="ph" value="{{ old('ph') }}" placeholder="Contoh: 7,5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">DO / Oksigen Terlarut (mg/L)</label>
                    <input type="text" inputmode="decimal" name="do_mg_l" value="{{ old('do_mg_l') }}" placeholder="Contoh: 5,2" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Suhu Air (°C)</label>
                    <input type="text" inputmode="decimal" name="temperature_c" value="{{ old('temperature_c') }}" placeholder="Contoh: 28,5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Kecerahan Air (cm)</label>
                    <input type="text" inputmode="decimal" name="transparency_cm" value="{{ old('transparency_cm') }}" placeholder="Contoh: 35" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Warna Air (Opsional)</label>
                <input type="text" name="water_color" value="{{ old('water_color') }}" placeholder="Contoh: Hijau Muda, Cokelat Bioflok, Jernih" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Kualitas Air</button>
                <a href="/tenant/logs/water" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>