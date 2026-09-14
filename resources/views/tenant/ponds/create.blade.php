<x-layouts.app>
    <x-slot:title>Tambah Kolam Baru</x-slot:title>

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2>Form Tambah Kolam</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/tenant/ponds" method="POST">
            @csrf
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Kode Kolam *</label>
                <input type="text" name="code" value="{{ old('code') }}" required placeholder="Contoh: K-01" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Nama / Label Kolam *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Kolam Bioflok Nila 1" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Bentuk Kolam *</label>
                <select name="shape" id="shape_select" onchange="toggleDimensions()" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="bulat" {{ old('shape') == 'bulat' ? 'selected' : '' }}>Bulat / Lingkaran</option>
                    <option value="persegi" {{ old('shape') == 'persegi' ? 'selected' : '' }}>Persegi (Sama Sisi)</option>
                    <option value="persegi panjang" {{ old('shape') == 'persegi panjang' ? 'selected' : '' }}>Persegi Panjang</option>
                    <option value="lainnya" {{ old('shape') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            {{-- Input Khusus Kolam Bulat --}}
            <div id="circle_inputs" style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Diameter (Meter)</label>
                <input type="text" inputmode="decimal" name="diameter" value="{{ old('diameter') }}" placeholder="Contoh: 3 atau 3,5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            {{-- Input Khusus Kolam Persegi / Persegi Panjang --}}
            <div id="rectangle_inputs" style="display: none; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Panjang (Meter)</label>
                    <input type="text" inputmode="decimal" name="length" value="{{ old('length') }}" placeholder="Contoh: 5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: bold;">Lebar (Meter)</label>
                    <input type="text" inputmode="decimal" name="width" value="{{ old('width') }}" placeholder="Contoh: 2" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Kedalaman Air (Meter) *</label>
                <input type="text" inputmode="decimal" name="depth" value="{{ old('depth') }}" required placeholder="Contoh: 1,2" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Kategori / Tipe Kolam (Opsional)</label>
                <select name="pond_type_id" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Pilih Tipe Kolam --</option>
                    @foreach($pondTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="status" value="kosong">

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan Kolam</button>
                <a href="/tenant/ponds" style="background: #9ca3af; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">Batal</a>
            </div>
        </form>
    </div>

    <script>
        function toggleDimensions() {
            const shape = document.getElementById('shape_select').value;
            const circleInputs = document.getElementById('circle_inputs');
            const rectangleInputs = document.getElementById('rectangle_inputs');

            if (shape === 'persegi' || shape === 'persegi panjang') {
                circleInputs.style.display = 'none';
                rectangleInputs.style.display = 'grid';
            } else {
                circleInputs.style.display = 'block';
                rectangleInputs.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', toggleDimensions);
    </script>
</x-layouts.app>