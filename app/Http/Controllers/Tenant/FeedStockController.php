<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use App\Models\DailyFeedLog;
use App\Models\CashflowCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedStockController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;

        // 1. Cari ID Kategori Cashflow terkait Pakan
        $feedCategoryIds = CashflowCategory::where('name', 'LIKE', '%pakan%')
            ->pluck('id');

        // 2. Hitung Total Pembelian Pakan dari Cashflow (Kg)
        $totalPurchasedKg = CashTransaction::where('tenant_id', $tenantId)
            ->whereIn('cashflow_category_id', $feedCategoryIds)
            ->sum('quantity');

        // 3. Hitung Total Penggunaan Pakan dari DailyFeedLog (Kg)
        $totalUsedKg = DailyFeedLog::where('tenant_id', $tenantId)
            ->sum('amount_kg');

        // 4. Kalkulasi Sisa Stok
        $currentStockKg = $totalPurchasedKg - $totalUsedKg;

        // 5. Riwayat Pakan Masuk (Pembelian Cashflow Terakhir)
        $pembelianLogs = CashTransaction::with(['contact', 'category'])
            ->where('tenant_id', $tenantId)
            ->whereIn('cashflow_category_id', $feedCategoryIds)
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();

        // 6. Riwayat Pemakaian Pakan Harian Terakhir
        $pemakaianLogs = DailyFeedLog::with(['batch.pond', 'feedType', 'user'])
            ->where('tenant_id', $tenantId)
            ->orderBy('feed_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('tenant.inventory.feed.index', compact(
            'totalPurchasedKg',
            'totalUsedKg',
            'currentStockKg',
            'pembelianLogs',
            'pemakaianLogs'
        ));
    }
}