<x-layouts.app>
    <x-slot:title>Catat Log Pakan Harian</x-slot:title>

    <div style="max-width: 650px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: bold; color: #0f172a;">Form Catat Pakan Harian</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tenant.logs.feed.store') }}" method="POST">
            @csrf

            <!-- Pilih Batch -->
            <div style="margin-bottom: 1rem;">
                {{-- <label style="display: block; margin-bottom: 4px; font-weight: bold;">Pilih Siklus / Kolam Aktif *</label> --}}
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Siklus/Kolam</label>
                {{-- <select name="batch_id" id="batch_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Pilih Kolam Aktif --</option>
                    @foreach($activeBatches as $batch)
                        <option value="{{ $batch->id }}" 
                            {{ old('batch_id', $selectedBatchId) == $batch->id ? 'selected' : '' }}>
                            {{ $batch->pond->name ?? '-' }} ({{ $batch->batch_code }})
                        </option>
                    @endforeach
                </select> --}}
                @if($selectedBatchId)
                    {{-- Jika diklik dari tombol "+ Catat" spesifik batch (Tampilan Readonly) --}}
                    @php
                        $selectedBatch = $activeBatches->firstWhere('id', $selectedBatchId);
                    @endphp

                    <input type="hidden" name="batch_id" value="{{ $selectedBatchId }}">
                    
                    <select id="batch_id" disabled style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; background-color: #f1f5f9; color: #475569; cursor: not-allowed;">
                        <option value="{{ $selectedBatchId }}">
                            {{ $selectedBatch->pond->name ?? '-' }} ({{ $selectedBatch->batch_code ?? '-' }})
                        </option>
                    </select>
                    <small style="color: #64748b; font-size: 11px;">Batch otomatis terpilih dari kolam yang dipilih.</small>
                @else
                    {{-- Jika diklik dari tombol "+ Catat Pakan Baru" umum (Dropdown Editable) --}}
                    <select name="batch_id" id="batch_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">-- Pilih Kolam Aktif --</option>
                        @foreach($activeBatches as $batch)
                            <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                {{ $batch->pond->name ?? '-' }} ({{ $batch->batch_code }})
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <!-- Tanggal & Jenis Pakan -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Tanggal Pakan *</label>
                    <input type="date" name="feed_date" value="{{ old('feed_date', date('Y-m-d')) }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Jenis Pakan</label>
                    <select name="feed_type_id" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">-- Pilih Jenis Pakan --</option>
                        @foreach($feedTypes as $type)
                            <option value="{{ $type->id }}" {{ old('feed_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Frekuensi & Pakan Satuan -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Frekuensi Pemberian *</label>
                    <input type="number" id="feeding_frequency" name="feeding_frequency" value="{{ old('feeding_frequency', 1) }}" min="1" oninput="calculateRealtime('freq')" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <small style="color: #6b7280; font-size: 11px;">Berapa kali ngasih pakan sehari</small>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Pakan Per Sesi (Gram)</label>
                    <input type="text" inputmode="decimal" id="amount_per_feed_g" name="amount_per_feed_g" value="{{ old('amount_per_feed_g') }}" placeholder="Contoh: 500" oninput="calculateRealtime('per_feed')" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <small style="color: #6b7280; font-size: 11px;">Jumlah pakan sekali tebar</small>
                </div>
            </div>

            <!-- Total Pakan (Kg) & Respon -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Total Pakan Harian (Kg) *</label>
                    <input type="text" inputmode="decimal" id="amount_kg" name="amount_kg" value="{{ old('amount_kg') }}" placeholder="Contoh: 1,5" oninput="calculateRealtime('total_kg')" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <small style="color: #6b7280; font-size: 11px;">Otomatis dari kalkulasi atau isi langsung</small>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Respon Pakan *</label>
                    <select name="appetite_response" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="sangat_baik" {{ old('appetite_response') == 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                        <option value="baik" {{ old('appetite_response', 'baik') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="kurang" {{ old('appetite_response') == 'kurang' ? 'selected' : '' }}>Kurang</option>
                        <option value="buruk" {{ old('appetite_response') == 'buruk' ? 'selected' : '' }}>Buruk</option>
                    </select>
                </div>
            </div>

            <!-- Widget Realtime Preview -->
            <div id="realtime_preview" style="display: none; background: #eff6ff; border: 1px solid #bfdbfe; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <div style="font-size: 0.85rem; color: #1e40af; font-weight: bold;">Kalkulasi Pakan Otomatis:</div>
                <div style="display: flex; gap: 1.5rem; margin-top: 4px; font-size: 0.9rem; color: #1e3a8a;">
                    <div>Per Sesi: <strong id="preview_per_feed">0</strong> Gram</div>
                    <div>Frekuensi: <strong id="preview_freq">0</strong> x / Hari</div>
                    <div>Total Harian: <strong id="preview_total_kg">0</strong> Kg</div>
                </div>
            </div>

            <!-- Catatan -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Catatan / Keterangan</label>
                <textarea name="notes" rows="2" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Catatan opsional...">{{ old('notes') }}</textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Log Pakan</button>
                <a href="{{ route('tenant.logs.feed.index') }}" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Batal</a>
            </div>
        </form>
    </div>

    <script>
        function parseValue(val) {
            if (!val) return 0;
            return parseFloat(val.toString().replace(',', '.')) || 0;
        }

        function formatDecimal(num) {
            return num.toFixed(2).replace('.', ',');
        }

        function calculateRealtime(source) {
            const freq = Math.max(1, parseValue(document.getElementById('feeding_frequency').value));
            let perFeedG = parseValue(document.getElementById('amount_per_feed_g').value);
            let totalKg = parseValue(document.getElementById('amount_kg').value);

            if (source === 'per_feed' || source === 'freq') {
                if (perFeedG > 0) {
                    totalKg = (perFeedG * freq) / 1000;
                    document.getElementById('amount_kg').value = formatDecimal(totalKg);
                }
            } else if (source === 'total_kg') {
                if (totalKg > 0 && freq > 0) {
                    perFeedG = (totalKg * 1000) / freq;
                    document.getElementById('amount_per_feed_g').value = formatDecimal(perFeedG);
                }
            }

            const previewBox = document.getElementById('realtime_preview');
            if (perFeedG > 0 || totalKg > 0) {
                previewBox.style.display = 'block';
                document.getElementById('preview_per_feed').innerText = formatDecimal(perFeedG);
                document.getElementById('preview_freq').innerText = freq;
                document.getElementById('preview_total_kg').innerText = formatDecimal(totalKg);
            } else {
                previewBox.style.display = 'none';
            }
        }
    </script>
</x-layouts.app>