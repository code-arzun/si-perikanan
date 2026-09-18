<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\CashflowKeterangan;
use App\Models\CashflowCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CashflowCategoryController extends Controller
{
    public function index()
    {
        $categories = CashflowCategory::orderBy('type')
            ->orderBy('keterangan')
            ->orderBy('name')
            ->get();

        return view('admin.cashflow-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:pemasukan,pengeluaran'],
            'keterangan'  => ['required', Rule::enum(CashflowKeterangan::class)],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        CashflowCategory::create([
            'name'        => $request->name,
            'type'        => $request->type,
            'keterangan'  => $request->keterangan,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Kategori master berhasil ditambahkan!');
    }

    public function update(Request $request, CashflowCategory $cashflowCategory)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:pemasukan,pengeluaran'],
            'keterangan'  => ['required', Rule::enum(CashflowKeterangan::class)],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $cashflowCategory->update([
            'name'        => $request->name,
            'type'        => $request->type,
            'keterangan'  => $request->keterangan,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Kategori master berhasil diperbarui!');
    }

    public function destroy(CashflowCategory $cashflowCategory)
    {
        $cashflowCategory->delete();

        return redirect()->back()->with('success', 'Kategori master berhasil dihapus!');
    }
}