<x-layouts.app>
    <x-slot:title>Master Kategori Cashflow - Superadmin</x-slot:title>

    <style>
        .container-fluid { padding: 1.5rem; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
        .btn-add { background: #2563eb; color: white; border: none; padding: 0.625rem 1.25rem; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; }
        .btn-add:hover { background: #1d4ed8; }

        /* Navigation Tabs */
        .tab-container { display: flex; gap: 0.5rem; margin-bottom: 1rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; }
        .tab-btn { padding: 0.5rem 1.25rem; font-weight: 600; border-radius: 6px; border: none; background: transparent; color: #64748b; cursor: pointer; transition: all 0.2s; }
        .tab-btn:hover { background: #f1f5f9; color: #0f172a; }
        .tab-btn.active-expense { background: #fee2e2; color: #b91c1c; }
        .tab-btn.active-income { background: #dcfce7; color: #15803d; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Card & Table */
        .card { background: white; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        .table-responsive { width: 100%; overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.925rem; }
        .table th { background: #f8fafc; padding: 0.875rem 1rem; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        .table tr:hover { background: #f8fafc; }

        /* Badges & Action Buttons */
        .badge { display: inline-block; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-keterangan { background: #e0f2fe; color: #0369a1; text-transform: capitalize; }

        .btn-action { padding: 0.375rem 0.75rem; border-radius: 4px; font-size: 0.8125rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; margin-right: 0.25rem; }
        .btn-edit { background: #fef3c7; color: #b45309; }
        .btn-edit:hover { background: #fde68a; }
        .btn-delete { background: #fee2e2; color: #b91c1c; }
        .btn-delete:hover { background: #fca5a5; }

        /* Alert */
        .alert { padding: 0.875rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-success { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .alert-danger { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

        /* Modal Styles */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(3px); z-index: 1000; justify-content: center; align-items: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-card { background: white; width: 100%; max-width: 500px; border-radius: 12px; padding: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0; }
        .modal-title { font-size: 1.125rem; font-weight: 700; color: #0f172a; margin: 0; }
        .modal-close { background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer; }

        /* Form */
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #334155; }
        .form-control { width: 100%; padding: 0.625rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .form-footer { display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem; }
    </style>

    <div class="container-fluid">
        <!-- Header Page -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Master Kategori Cashflow</h1>
                <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">Kelola standar kategori pemasukan dan pengeluaran sistem.</p>
            </div>
            <button type="button" class="btn-add" onclick="openModal('addModal')">
                <span>+</span> Tambah Kategori
            </button>
        </div>

        <!-- Alert Notification -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Navigation Tabs -->
        <div class="tab-container">
            <button class="tab-btn active-expense" id="tab-btn-pengeluaran" onclick="switchCategoryTab('pengeluaran')">
                🔴 Pengeluaran
            </button>
            <button class="tab-btn" id="tab-btn-pemasukan" onclick="switchCategoryTab('pemasukan')">
                🟢 Pemasukan
            </button>
        </div>

        <!-- TAB CONTENT 1: TABEL KATEGORI PENGELUARAN -->
        <div id="tab-pengeluaran" class="tab-content active">
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Tampilan</th>
                                <th>Keterangan</th>
                                <th>Deskripsi</th>
                                <th style="width: 140px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $expNo = 0; @endphp
                            @foreach($categories as $category)
                                @if($category->type == 'pengeluaran')
                                    @php $expNo++; @endphp
                                    <tr>
                                        <td>{{ $expNo }}</td>
                                        <td><strong>{{ $category->full_name }}</strong></td>
                                        <td>
                                            <span class="badge badge-keterangan">
                                                {{ $category->keterangan instanceof \App\Enums\CashflowKeterangan ? $category->keterangan->label() : $category->keterangan }}
                                            </span>
                                        </td>
                                        <td>{{ $category->description ?? '-' }}</td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn-action btn-edit" 
                                                onclick="editCategory({{ json_encode([
                                                    'id' => $category->id,
                                                    'name' => $category->name,
                                                    'type' => $category->type,
                                                    'keterangan' => $category->keterangan->value ?? $category->keterangan,
                                                    'description' => $category->description
                                                ]) }})">
                                                Edit
                                            </button>
                                            
                                            <form action="{{ route('admin.cashflow-categories.destroy', $category->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if($expNo === 0)
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 2rem; color: #94a3b8;">
                                        Belum ada data kategori pengeluaran.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT 2: TABEL KATEGORI PEMASUKAN -->
        <div id="tab-pemasukan" class="tab-content">
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Tampilan</th>
                                <th>Keterangan</th>
                                <th>Deskripsi</th>
                                <th style="width: 140px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $incNo = 0; @endphp
                            @foreach($categories as $category)
                                @if($category->type == 'pemasukan')
                                    @php $incNo++; @endphp
                                    <tr>
                                        <td>{{ $incNo }}</td>
                                        <td><strong>{{ $category->full_name }}</strong></td>
                                        <td>
                                            <span class="badge badge-keterangan">
                                                {{ $category->keterangan instanceof \App\Enums\CashflowKeterangan ? $category->keterangan->label() : $category->keterangan }}
                                            </span>
                                        </td>
                                        <td>{{ $category->description ?? '-' }}</td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn-action btn-edit" 
                                                onclick="editCategory({{ json_encode([
                                                    'id' => $category->id,
                                                    'name' => $category->name,
                                                    'type' => $category->type,
                                                    'keterangan' => $category->keterangan->value ?? $category->keterangan,
                                                    'description' => $category->description
                                                ]) }})">
                                                Edit
                                            </button>
                                            
                                            <form action="{{ route('admin.cashflow-categories.destroy', $category->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if($incNo === 0)
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 2rem; color: #94a3b8;">
                                        Belum ada data kategori pemasukan.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div id="addModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Kategori Master</h3>
                <button type="button" class="modal-close" onclick="closeModal('addModal')">&times;</button>
            </div>
            <form action="{{ route('admin.cashflow-categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Tipe Transaksi *</label>
                    <select name="type" class="form-control" required>
                        <option value="pengeluaran">Pengeluaran</option>
                        <option value="pemasukan">Pemasukan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan *</label>
                    <select name="keterangan" class="form-control" required>
                        @foreach(\App\Enums\CashflowKeterangan::cases() as $case)
                            <option value="{{ $case->value }}">{{ $case->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Pakan / Gaji Staf" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Penjelasan singkat..."></textarea>
                </div>

                <div class="form-footer">
                    <button type="button" class="btn-action" style="background: #e2e8f0;" onclick="closeModal('addModal')">Batal</button>
                    <button type="submit" class="btn-add">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title">Edit Kategori Master</h3>
                <button type="button" class="modal-close" onclick="closeModal('editModal')">&times;</button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Tipe Transaksi *</label>
                    <select name="type" id="edit_type" class="form-control" required>
                        <option value="pengeluaran">Pengeluaran</option>
                        <option value="pemasukan">Pemasukan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan *</label>
                    <select name="keterangan" id="edit_keterangan" class="form-control" required>
                        @foreach(\App\Enums\CashflowKeterangan::cases() as $case)
                            <option value="{{ $case->value }}">{{ $case->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-footer">
                    <button type="button" class="btn-action" style="background: #e2e8f0;" onclick="closeModal('editModal')">Batal</button>
                    <button type="submit" class="btn-add">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function editCategory(data) {
            document.getElementById('editForm').action = '/admin/cashflow-categories/' + data.id;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_type').value = data.type;
            document.getElementById('edit_keterangan').value = data.keterangan;
            document.getElementById('edit_description').value = data.description || '';
            
            openModal('editModal');
        }

        function switchCategoryTab(type) {
            const tabPengeluaran = document.getElementById('tab-pengeluaran');
            const tabPemasukan = document.getElementById('tab-pemasukan');
            const btnPengeluaran = document.getElementById('tab-btn-pengeluaran');
            const btnPemasukan = document.getElementById('tab-btn-pemasukan');

            if (type === 'pengeluaran') {
                tabPengeluaran.classList.add('active');
                tabPemasukan.classList.remove('active');

                btnPengeluaran.classList.add('active-expense');
                btnPemasukan.classList.remove('active-income');
            } else {
                tabPemasukan.classList.add('active');
                tabPengeluaran.classList.remove('active');

                btnPemasukan.classList.add('active-income');
                btnPengeluaran.classList.remove('active-expense');
            }
        }

        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('active');
            }
        });
    </script>
</x-layouts.app>