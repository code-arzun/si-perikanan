<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use App\Models\CashflowCategory; // Import Model
use App\Models\Contact;
use App\Http\Requests\Tenant\CashTransactionRequest;
use Illuminate\Http\Request;

class CashTransactionController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        // Eager load relasi category & contact
        $query = CashTransaction::with(['contact', 'category'])->where('tenant_id', $tenantId);

        if ($request->filled('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(15);

        // Ringkasan Keuangan
        $totalIncome  = CashTransaction::where('tenant_id', $tenantId)->where('type', 'income')->sum('amount');
        $totalExpense = CashTransaction::where('tenant_id', $tenantId)->where('type', 'expense')->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        // Data untuk Form Modal
        $contacts   = Contact::where('tenant_id', $tenantId)->get();
        $categories = CashflowCategory::orderBy('type')->orderBy('keterangan')->orderBy('name')->get();

        return view('tenant.finance.index', compact(
            'transactions', 
            'totalIncome', 
            'totalExpense', 
            'balance', 
            'contacts', 
            'categories'
        ));
    }

    public function store(CashTransactionRequest $request)
    {
        $validated = $request->validated();
        $validated['tenant_id'] = auth()->user()->tenant_id;

        CashTransaction::create($validated);

        return back()->with('success', 'Transaksi keuangan berhasil dicatat!');
    }

    public function destroy(CashTransaction $finance)
    {
        if ($finance->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $finance->delete();
        return back()->with('success', 'Catatan transaksi berhasil dihapus!');
    }
}