<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\PondRequest;
use App\Models\Pond;
use App\Models\PondType;

class PondController extends Controller
{
    public function index()
    {
        $ponds = Pond::with('activeBatch')->latest()->get();

        return view('tenant.ponds.index', compact('ponds'));
    }

    public function create()
    {
        $pondTypes = PondType::all();

        return view('tenant.ponds.create', compact('pondTypes'));
    }

    public function store(PondRequest $request)
    {
        Pond::create($request->validated());

        return redirect('/tenant/ponds')->with('success', 'Kolam berhasil ditambahkan!');
    }

    public function destroy(Pond $pond)
    {
        $pond->delete();

        return redirect('/tenant/ponds')->with('success', 'Kolam berhasil dihapus!');
    }
}