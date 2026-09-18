<x-layouts.app>
    <x-slot:title>Manajemen Staf & Pegawai</x-slot:title>

    <style>
        .container-fluid { padding: 1.5rem; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
        .btn-add { background: #2563eb; color: white; border: none; padding: 0.625rem 1.25rem; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; }
        .btn-add:hover { background: #1d4ed8; }

        .card { background: white; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        .table-responsive { width: 100%; overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.925rem; }
        .table th { background: #f8fafc; padding: 0.875rem 1rem; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        .table tr:hover { background: #f8fafc; }

        .badge { display: inline-block; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-keuangan { background: #e0f2fe; color: #0369a1; }
        .badge-kolam { background: #fef3c7; color: #b45309; }

        .btn-action { padding: 0.375rem 0.75rem; border-radius: 4px; font-size: 0.8125rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; margin-right: 0.25rem; }
        .btn-edit { background: #fef3c7; color: #b45309; }
        .btn-edit:hover { background: #fde68a; }
        .btn-delete { background: #fee2e2; color: #b91c1c; }
        .btn-delete:hover { background: #fca5a5; }

        .alert { padding: 0.875rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-success { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .alert-danger { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(3px); z-index: 1000; justify-content: center; align-items: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-card { background: white; width: 100%; max-width: 500px; border-radius: 12px; padding: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0; }
        .modal-title { font-size: 1.125rem; font-weight: 700; color: #0f172a; margin: 0; }
        .modal-close { background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer; }

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
                <h1 class="page-title">Manajemen Staf & Pegawai</h1>
                <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">Kelola akun tim operasional dan hak aksesnya di aplikasi.</p>
            </div>
            <button type="button" class="btn-add" onclick="openModal('addModal')">
                <span>+</span> Tambah Pegawai
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

        <!-- Table Employees -->
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Username / Email</th>
                            <th>No. WhatsApp</th>
                            <th>Hak Akses (Role)</th>
                            <th style="width: 140px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $employee)
                            @php $roleName = $employee->roles->first()?->name; @endphp
                            <tr>
                                <td><strong>{{ $employee->name }}</strong></td>
                                <td>
                                    {{ $employee->username }}<br>
                                    <small style="color: #64748b;">{{ $employee->email }}</small>
                                </td>
                                <td>{{ $employee->phone ?? '-' }}</td>
                                <td>
                                    @if($roleName == 'tenant_admin_keuangan')
                                        <span class="badge badge-keuangan">Admin Keuangan</span>
                                    @elseif($roleName == 'tenant_staf_kolam')
                                        <span class="badge badge-kolam">Staf Kolam</span>
                                    @else
                                        <span class="badge" style="background: #f1f5f9; color: #475569;">{{ $roleName }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn-action btn-edit" onclick="editEmployee({{ json_encode([
                                        'id' => $employee->id,
                                        'name' => $employee->name,
                                        'email' => $employee->email,
                                        'username' => $employee->username,
                                        'phone' => $employee->phone,
                                        'role' => $roleName,
                                    ]) }})">
                                        Edit
                                    </button>

                                    <form action="{{ route('tenant.employees.destroy', $employee->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hapus akses pegawai ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: #94a3b8;">
                                    Belum ada data pegawai yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top: 1rem;">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Tambah Pegawai -->
    <div id="addModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Pegawai Baru</h3>
                <button type="button" class="modal-close" onclick="closeModal('addModal')">&times;</button>
            </div>
            <form action="{{ route('tenant.employees.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Hak Akses / Role *</label>
                    <select name="role" class="form-control" required>
                        <option value="tenant_staf_kolam">Staf Kolam (Pakan, Monitoring & Panen)</option>
                        <option value="tenant_admin_keuangan">Admin Keuangan (Kas & Laporan)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-control" placeholder="budisantoso" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="budi@example.com">
                </div>

                <div class="form-group">
                    <label class="form-label">No. WhatsApp / HP</label>
                    <input type="text" name="phone" class="form-control" placeholder="081234567890">
                </div>

                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required minlength="8">
                </div>

                <div class="form-footer">
                    <button type="button" class="btn-action" style="background: #e2e8f0; padding: 0.625rem 1rem;" onclick="closeModal('addModal')">Batal</button>
                    <button type="submit" class="btn-add">Simpan Pegawai</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Pegawai -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title">Edit Data Pegawai</h3>
                <button type="button" class="modal-close" onclick="closeModal('editModal')">&times;</button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Hak Akses / Role *</label>
                    <select name="role" id="edit_role" class="form-control" required>
                        <option value="tenant_staf_kolam">Staf Kolam</option>
                        <option value="tenant_admin_keuangan">Admin Keuangan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" id="edit_username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">No. WhatsApp / HP</label>
                    <input type="text" name="phone" id="edit_phone" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" minlength="8">
                </div>

                <div class="form-footer">
                    <button type="button" class="btn-action" style="background: #e2e8f0; padding: 0.625rem 1rem;" onclick="closeModal('editModal')">Batal</button>
                    <button type="submit" class="btn-add">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function editEmployee(data) {
            document.getElementById('editForm').action = '/tenant/employees/' + data.id;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_username').value = data.username;
            document.getElementById('edit_email').value = data.email;
            document.getElementById('edit_phone').value = data.phone || '';
            document.getElementById('edit_role').value = data.role;

            openModal('editModal');
        }

        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('active');
            }
        });
    </script>
</x-layouts.app>