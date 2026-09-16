<x-layouts.app>
    <x-slot:title>Arus Kas & Keuangan - Workspace Tenant</x-slot:title>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="margin: 0; color: #1e293b; font-size: 1.5rem;">💰 Arus Kas & Keuangan</h2>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.9rem;">
                Pencatatan pemasukan dan pengeluaran operasional tambak secara real-time.
            </p>
        </div>
    </div>

    {{-- Pesan Sukses Notifikasi --}}
    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #15803d; padding: 12px 16px; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- KPI RINGKASAN KEUANGAN --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: white; border-radius: 8px; padding: 1.25rem; border-left: 4px solid #16a34a; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <small style="color: #64748b; font-weight: bold;">TOTAL PEMASUKAN</small>
            <h3 style="margin: 8px 0 0 0; color: #15803d; font-size: 1.4rem;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
        </div>
        <div style="background: white; border-radius: 8px; padding: 1.25rem; border-left: 4px solid #dc2626; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <small style="color: #64748b; font-weight: bold;">TOTAL PENGELUARAN</small>
            <h3 style="margin: 8px 0 0 0; color: #b91c1c; font-size: 1.4rem;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
        </div>
        <div style="background: white; border-radius: 8px; padding: 1.25rem; border-left: 4px solid #2563eb; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <small style="color: #64748b; font-weight: bold;">SALDO / SISA KAS</small>
            <h3 style="margin: 8px 0 0 0; color: #1d4ed8; font-size: 1.4rem;">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 340px 1fr; gap: 1.5rem; align-items: start;">
        
        {{-- FORM INPUT TRANSAKSI BARU --}}
        <div style="background: white; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; margin-bottom: 1rem; color: #0f172a; font-size: 1.05rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                ➕ Catat Transaksi Baru
            </h3>

            <form action="{{ route('tenant.finance.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">
                        Jenis Transaksi <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="type" required style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; background: white;">
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>🔴 Pengeluaran (Expense)</option>
                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>🟢 Pemasukan (Income)</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">
                        Nominal (Rp) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="amount" value="{{ old('amount') }}" placeholder="Contoh: 500000" required style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem;">
                    @error('amount')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">
                        Tanggal Transaksi <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">
                        Kategori <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="category" value="{{ old('category') }}" placeholder="Contoh: Pembelian Pakan, Hasil Panen, Listrik" required style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem;">
                </div>

                {{-- DROPDOWN RELASI KONTAK SUPPLIER / BUYER --}}
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">
                        Pihak Terkait (Supplier / Buyer)
                    </label>
                    <select name="contact_id" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; background: white;">
                        <option value="">-- Tidak Ada / Umum --</option>
                        @foreach($contacts as $contact)
                            <option value="{{ $contact->id }}" {{ old('contact_id') == $contact->id ? 'selected' : '' }}>
                                {{ $contact->name }} ({{ ucfirst($contact->type) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: bold; color: #334155; margin-bottom: 4px;">Keterangan Opsional</label>
                    <textarea name="description" rows="2" placeholder="Catatan tambahan..." style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; resize: vertical;">{{ old('description') }}</textarea>
                </div>

                <button type="submit" style="width: 100%; background: #2563eb; color: white; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.9rem;">
                    Simpan Transaksi
                </button>
            </form>
        </div>

        {{-- TABEL RIWAYAT TRANSAKSI KAS --}}
        <div style="background: white; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            
            <div style="display: flex; gap: 8px; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <a href="{{ route('tenant.finance.index') }}" style="padding: 6px 14px; border-radius: 20px; text-decoration: none; font-size: 0.85rem; font-weight: bold; {{ !request('type') ? 'background: #0f172a; color: white;' : 'background: #f1f5f9; color: #475569;' }}">
                    Semua Transaksi
                </a>
                <a href="{{ route('tenant.finance.index', ['type' => 'income']) }}" style="padding: 6px 14px; border-radius: 20px; text-decoration: none; font-size: 0.85rem; font-weight: bold; {{ request('type') == 'income' ? 'background: #16a34a; color: white;' : 'background: #f1f5f9; color: #475569;' }}">
                    🟢 Pemasukan
                </a>
                <a href="{{ route('tenant.finance.index', ['type' => 'expense']) }}" style="padding: 6px 14px; border-radius: 20px; text-decoration: none; font-size: 0.85rem; font-weight: bold; {{ request('type') == 'expense' ? 'background: #dc2626; color: white;' : 'background: #f1f5f9; color: #475569;' }}">
                    🔴 Pengeluaran
                </a>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                        <th style="padding: 10px;">Tanggal</th>
                        <th style="padding: 10px;">Kategori</th>
                        <th style="padding: 10px;">Pihak Terkait</th>
                        <th style="padding: 10px; text-align: right;">Nominal</th>
                        <th style="padding: 10px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px; color: #64748b; font-size: 0.85rem;">
                                {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y') }}
                            </td>
                            <td style="padding: 10px; font-weight: bold; color: #1e293b;">
                                {{ $trx->category }}
                                @if($trx->description)
                                    <div style="font-size: 0.78rem; font-weight: normal; color: #64748b;">{{ $trx->description }}</div>
                                @endif
                            </td>
                            <td style="padding: 10px; color: #334155;">
                                {{ $trx->contact->name ?? '-' }}
                            </td>
                            <td style="padding: 10px; text-align: right; font-weight: bold; {{ $trx->type === 'income' ? 'color: #16a34a;' : 'color: #dc2626;' }}">
                                {{ $trx->type === 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                            <td style="padding: 10px; text-align: center;">
                                <form action="{{ route('tenant.finance.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan transaksi ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Transaksi" style="background: #ef4444; color: white; border: none; padding: 5px 8px; border-radius: 4px; cursor: pointer; font-size: 13px;">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 2rem; text-align: center; color: #94a3b8;">
                                Belum ada catatan transaksi keuangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(is_object($transactions) && method_exists($transactions, 'links'))
                <div style="margin-top: 1rem;">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.app>