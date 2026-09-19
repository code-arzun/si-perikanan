<x-layouts.app>
    <x-slot:title>Pencatatan Transaksi Kas</x-slot:title>

    <style>
        .container-fluid { padding: 1.5rem; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
        
        /* Summary Cards */
        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .summary-card { background: white; border-radius: 10px; border: 1px solid #e2e8f0; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .summary-label { font-size: 0.875rem; color: #64748b; font-weight: 500; margin-bottom: 0.5rem; }
        .summary-value { font-size: 1.5rem; font-weight: 700; margin: 0; }
        .text-income { color: #16a34a; }
        .text-expense { color: #dc2626; }
        .text-balance { color: #2563eb; }

        /* Buttons & Tab Navigation */
        .btn-add { background: #2563eb; color: white; border: none; padding: 0.625rem 1.25rem; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; }
        .btn-add:hover { background: #1d4ed8; }
        
        .tab-container { display: flex; gap: 0.5rem; margin-bottom: 1rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; }
        .tab-btn { padding: 0.5rem 1.25rem; font-weight: 600; border-radius: 6px; border: none; background: transparent; color: #64748b; cursor: pointer; transition: all 0.2s; }
        .tab-btn:hover { background: #f1f5f9; color: #0f172a; }
        .tab-btn.active-expense { background: #fee2e2; color: #b91c1c; }
        .tab-btn.active-income { background: #dcfce7; color: #15803d; }

        .card { background: white; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        .table-responsive { width: 100%; overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.925rem; }
        .table th { background: #f8fafc; padding: 0.875rem 1rem; font-weight: 600; color: #475569; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        .table tr:hover { background: #f8fafc; }

        /* Badges & Actions */
        .btn-delete { background: #fee2e2; color: #b91c1c; padding: 0.375rem 0.75rem; border-radius: 4px; font-size: 0.8125rem; font-weight: 600; border: none; cursor: pointer; }
        .btn-delete:hover { background: #fca5a5; }

        /* Modal Styles */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(3px); z-index: 1000; justify-content: center; align-items: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-card { background: white; width: 100%; max-width: 550px; border-radius: 12px; padding: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-height: 90vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0; }
        .modal-title { font-size: 1.125rem; font-weight: 700; color: #0f172a; margin: 0; }
        .modal-close { background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer; }

        /* Form */
        .form-group { margin-bottom: 1rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .form-row-3 { display: grid; grid-template-columns: 1.2fr 0.8fr 1fr; gap: 0.75rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.375rem; color: #334155; }
        .form-control { width: 100%; padding: 0.625rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .form-control[readonly] { background-color: #f8fafc; font-weight: 700; color: #0f172a; }
        .form-footer { display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem; }
        
        .alert { padding: 0.875rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-success { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .alert-danger { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>

    <div class="container-fluid">
        <!-- Header Page -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Transaksi Kas Operasional</h1>
                <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">Catat rincian pemasukan dan pengeluaran keuangan usaha Anda.</p>
            </div>
            <button type="button" class="btn-add" onclick="openModal('addModal')">
                <span>+</span> Catat Transaksi
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

        <!-- Financial Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">Total Pemasukan</div>
                <div class="summary-value text-income">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Pengeluaran</div>
                <div class="summary-value text-expense">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Saldo Kas</div>
                <div class="summary-value text-balance">Rp {{ number_format($balance, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="tab-container">
            <button class="tab-btn active-expense" id="tab-btn-expense" onclick="switchTab('expense')">
                🔴 Pengeluaran
            </button>
            <button class="tab-btn" id="tab-btn-income" onclick="switchTab('income')">
                🟢 Pemasukan
            </button>
        </div>

        <!-- TAB CONTENT 1: TABEL PENGELUARAN -->
        <div id="tab-expense" class="tab-content active">
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 100px;">Tanggal</th>
                                <th>Kategori</th>
                                <th>Kontak</th>
                                <th style="text-align: right;">Harga Satuan</th>
                                <th style="text-align: center;">Jumlah</th>
                                <th style="text-align: right;">Total Akhir</th>
                                <th>Deskripsi</th>
                                <th style="width: 70px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $expenseCount = 0; @endphp
                            @foreach($transactions as $item)
                                @if($item->type == 'expense')
                                    @php $expenseCount++; @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->transaction_date)->format('d/m/Y') }}</td>
                                        <td><strong>{{ $item->category?->full_name ?? '-' }}</strong></td>
                                        <td>{{ $item->contact?->name ?? '-' }}</td>
                                        <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td style="text-align: center;">
                                            {{ floatval($item->quantity) }} 
                                            <span style="font-size: 0.8rem; color: #64748b;">
                                                {{ $item->unit instanceof \App\Enums\TransactionUnit ? $item->unit->value : $item->unit }}
                                            </span>
                                        </td>
                                        <td style="text-align: right; font-weight: 700;" class="text-expense">
                                            - Rp {{ number_format($item->amount, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $item->description ?? '-' }}</td>
                                        <td style="text-align: center;">
                                            <form action="{{ route('tenant.finance.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus catatan transaksi pengeluaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if($expenseCount === 0)
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 2rem; color: #94a3b8;">
                                        Belum ada catatan pengeluaran.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT 2: TABEL PEMASUKAN -->
        <div id="tab-income" class="tab-content">
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 100px;">Tanggal</th>
                                <th>Kategori</th>
                                <th>Kontak</th>
                                <th style="text-align: right;">Harga Satuan</th>
                                <th style="text-align: center;">Jumlah</th>
                                <th style="text-align: right;">Total Akhir</th>
                                <th>Deskripsi</th>
                                <th style="width: 70px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $incomeCount = 0; @endphp
                            @foreach($transactions as $item)
                                @if($item->type == 'income')
                                    @php $incomeCount++; @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->transaction_date)->format('d/m/Y') }}</td>
                                        <td><strong>{{ $item->category?->full_name ?? '-' }}</strong></td>
                                        <td>{{ $item->contact?->name ?? '-' }}</td>
                                        <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td style="text-align: center;">
                                            {{ floatval($item->quantity) }} 
                                            <span style="font-size: 0.8rem; color: #64748b;">
                                                {{ $item->unit instanceof \App\Enums\TransactionUnit ? $item->unit->value : $item->unit }}
                                            </span>
                                        </td>
                                        <td style="text-align: right; font-weight: 700;" class="text-income">
                                            + Rp {{ number_format($item->amount, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $item->description ?? '-' }}</td>
                                        <td style="text-align: center;">
                                            <form action="{{ route('tenant.finance.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus catatan transaksi pemasukan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if($incomeCount === 0)
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 2rem; color: #94a3b8;">
                                        Belum ada catatan pemasukan.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 1rem;">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Modal Catat Transaksi Kas -->
    <div id="addModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title">Catat Transaksi Kas Baru</h3>
                <button type="button" class="modal-close" onclick="closeModal('addModal')">&times;</button>
            </div>
            <form action="{{ route('tenant.finance.store') }}" method="POST">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jenis Transaksi *</label>
                        <select name="type" id="transaction_type" class="form-control" onchange="filterCategoriesByTransactionType()" required>
                            <option value="expense">Pengeluaran</option>
                            <option value="income">Pemasukan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Transaksi *</label>
                        <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori Cashflow *</label>
                    <select name="cashflow_category_id" id="cashflow_category_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    data-type="{{ $category->type == 'pemasukan' ? 'income' : 'expense' }}">
                                {{ $category->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Section Rincian Harga & Kuantitas -->
                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Harga Satuan (Rp) *</label>
                        <input type="number" name="unit_price" id="unit_price" class="form-control" placeholder="0" min="0" step="100" oninput="calculateTotal()" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah *</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="0.01" step="any" oninput="calculateTotal()" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Satuan *</label>
                        <select name="unit" class="form-control" required>
                            @foreach(\App\Enums\TransactionUnit::cases() as $unitCase)
                                <option value="{{ $unitCase->value }}">{{ $unitCase->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Preview Total Akhir -->
                <div class="form-group">
                    <label class="form-label" style="color: #2563eb;">Total Akhir / Nominal (Otomatis)</label>
                    <input type="text" id="amount_preview" class="form-control" value="Rp 0" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Kontak / Pihak Terkait</label>
                    <select name="contact_id" class="form-control">
                        <option value="">-- Tanpa Kontak / Umum --</option>
                        @foreach($contacts as $contact)
                            <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan / Deskripsi</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Penjelasan rinci transaksi..."></textarea>
                </div>

                <div class="form-footer">
                    <button type="button" style="background: #e2e8f0; padding: 0.625rem 1rem; border-radius: 6px; border: none; cursor: pointer;" onclick="closeModal('addModal')">Batal</button>
                    <button type="submit" class="btn-add">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script JavaScript -->
    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('active');
            filterCategoriesByTransactionType();
            calculateTotal();
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        // Hitung Otomatis Total Akhir = Harga Satuan * Jumlah
        function calculateTotal() {
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            const quantity = parseFloat(document.getElementById('quantity').value) || 0;
            const total = unitPrice * quantity;

            document.getElementById('amount_preview').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }

        // Switch Tab Antara Pengeluaran & Pemasukan
        function switchTab(type) {
            const tabExpense = document.getElementById('tab-expense');
            const tabIncome = document.getElementById('tab-income');
            const btnExpense = document.getElementById('tab-btn-expense');
            const btnIncome = document.getElementById('tab-btn-income');

            if (type === 'expense') {
                tabExpense.classList.add('active');
                tabIncome.classList.remove('active');

                btnExpense.classList.add('active-expense');
                btnIncome.classList.remove('active-income');
            } else {
                tabIncome.classList.add('active');
                tabExpense.classList.remove('active');

                btnIncome.classList.add('active-income');
                btnExpense.classList.remove('active-expense');
            }
        }

        // Dynamic Category Filter berdasarkan jenis transaksi
        function filterCategoriesByTransactionType() {
            const selectedType = document.getElementById('transaction_type').value;
            const categorySelect = document.getElementById('cashflow_category_id');
            const options = categorySelect.querySelectorAll('option');

            let firstValidOptionSet = false;

            options.forEach(option => {
                if (!option.value) return;

                const categoryType = option.getAttribute('data-type');

                if (categoryType === selectedType) {
                    option.style.display = 'block';
                    option.disabled = false;
                    
                    if (!firstValidOptionSet) {
                        option.selected = true;
                        firstValidOptionSet = true;
                    }
                } else {
                    option.style.display = 'none';
                    option.disabled = true;
                }
            });
        }

        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('active');
            }
        });
    </script>
</x-layouts.app>