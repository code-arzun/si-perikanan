<x-layouts.app>
    <x-slot:title>Stok & Mutasi Pakan</x-slot:title>

    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; }
        
        /* Summary Grid Cards */
        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .card-stat { background: white; border-radius: 10px; border: 1px solid #e2e8f0; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .stat-title { font-size: 0.8125rem; font-weight: 600; color: #64748b; text-transform: uppercase; }
        .stat-value { font-size: 1.75rem; font-weight: 800; margin-top: 0.25rem; }

        /* Table Card Container */
        .logs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .table-card { background: white; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .table-card-header { padding: 1rem 1.25rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #0f172a; display: flex; justify-content: space-between; align-items: center; }
        
        .table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        .table th { background: #ffffff; padding: 10px 12px; font-weight: 600; color: #475569; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #334155; }

        @media (max-width: 1024px) {
            .logs-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">📦 Inventaris & Mutasi Stok Pakan</h1>
            <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">Rekapitulasi otomatis pakan masuk dari transaksi Cashflow & pakan keluar dari Log Pakan Harian.</p>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="summary-grid">
        <div class="card-stat" style="border-left: 4px solid #10b981;">
            <div class="stat-title">Total Pakan Masuk (Pembelian)</div>
            <div class="stat-value" style="color: #059669;">
                {{ number_format($totalPurchasedKg, 2, ',', '.') }} <span style="font-size: 1rem;">Kg</span>
            </div>
            <small style="color: #64748b;">Diambil dari pencatatan Cashflow</small>
        </div>

        <div class="card-stat" style="border-left: 4px solid #ef4444;">
            <div class="stat-title">Total Pakan Keluar (Terpakai)</div>
            <div class="stat-value" style="color: #dc2626;">
                {{ number_format($totalUsedKg, 2, ',', '.') }} <span style="font-size: 1rem;">Kg</span>
            </div>
            <small style="color: #64748b;">Diambil dari Log Pakan Harian</small>
        </div>

        <div class="card-stat" style="border-left: 4px solid #2563eb;">
            <div class="stat-title">Sisa Stok Pakan Saat Ini</div>
            <div class="stat-value" style="color: #2563eb;">
                {{ number_format($currentStockKg, 2, ',', '.') }} <span style="font-size: 1rem;">Kg</span>
            </div>
            <small style="color: {{ $currentStockKg < 100 ? '#dc2626' : '#16a34a' }}; font-weight: bold;">
                {{ $currentStockKg < 100 ? '⚠️ Stok Pakan Menipis' : '✅ Stok Aman' }}
            </small>
        </div>
    </div>

    <!-- MUTASI LOGS GRID -->
    <div class="logs-grid">
        <!-- TABEL PAKAN MASUK (CASHFLOW) -->
        <div class="table-card">
            <div class="table-card-header">
                <span>📥 Riwayat Pakan Masuk (Pembelian)</span>
                <a href="{{ route('tenant.finance.index') ?? '#' }}" style="font-size: 0.8125rem; color: #2563eb; text-decoration: none;">Lihat Cashflow →</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Supplier / Kontak</th>
                        <th style="text-align: right;">Jumlah</th>
                        <th style="text-align: right;">Satuan</th>
                        <th style="text-align: right;">Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelianLogs as $item)
                        <tr>
                            <td><strong>{{ \Carbon\Carbon::parse($item->transaction_date)->format('d M Y') }}</strong></td>
                            <td>{{ $item->contact->name ?? 'Pembelian Umum' }}</td>
                            <td style="text-align: right; font-weight: bold; color: #059669;">
                                +{{ number_format($item->quantity, 2, ',', '.') }}
                            </td>
                            <td>{{ $item->unit?->label ?? $item->unit ?? '-' }}</td>
                            <td style="text-align: right; color: #64748b;">
                                Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #64748b; padding: 1.5rem;">
                                Belum ada transaksi pembelian pakan di Cashflow.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TABEL PAKAN KELUAR (DAILY FEED LOG) -->
        <div class="table-card">
            <div class="table-card-header">
                <span>Outflow Riwayat Pakan Terpakai</span>
                <a href="{{ route('tenant.logs.feed.index') }}" style="font-size: 0.8125rem; color: #2563eb; text-decoration: none;">Lihat Log Pakan →</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kolam / Batch</th>
                        <th>Jenis Pakan</th>
                        <th style="text-align: right;">Terpakai (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemakaianLogs as $log)
                        <tr>
                            <td><strong>{{ \Carbon\Carbon::parse($log->feed_date)->format('d M Y') }}</strong></td>
                            <td>
                                <strong>{{ $log->batch->pond->name ?? 'Kolam' }}</strong><br>
                                <small style="color: #2563eb;">{{ $log->batch->batch_code ?? '-' }}</small>
                            </td>
                            <td>{{ $log->feedType->name ?? 'Pakan Pelet' }}</td>
                            <td style="text-align: right; font-weight: bold; color: #dc2626;">
                                -{{ number_format($log->amount_kg, 2, ',', '.') }} Kg
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #64748b; padding: 1.5rem;">
                                Belum ada catatan pemakaian pakan harian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>