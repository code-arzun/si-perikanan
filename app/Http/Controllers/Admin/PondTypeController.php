<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PondType;
use App\Http\Requests\Admin\PondTypeRequest;

class PondTypeController extends Controller
{
    public function index()
    {
        $pondTypes = PondType::latest()->paginate(15);
        return view('admin.master.pond_types.index', compact('pondTypes'));
    }

    public function store(PondTypeRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        PondType::create($data);
        return back()->with('success', 'Jenis konstruksi kolam berhasil ditambahkan!');
    }

    public function update(PondTypeRequest $request, PondType $pondType)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        $pondType->update($data);
        return back()->with('success', 'Jenis konstruksi kolam berhasil diperbarui!');
    }

    public function destroy(PondType $pondType)
    {
        $pondType->delete();
        return back()->with('success', 'Jenis konstruksi kolam berhasil dihapus!');
    }
}