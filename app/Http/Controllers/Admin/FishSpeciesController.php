<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FishSpecies;
use App\Http\Requests\Admin\FishSpeciesRequest;

class FishSpeciesController extends Controller
{
    public function index()
    {
        $species = FishSpecies::latest()->paginate(15);
        return view('admin.master.fish_species.index', compact('species'));
    }

    public function store(FishSpeciesRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        FishSpecies::create($data);
        return back()->with('success', 'Spesies ikan berhasil ditambahkan!');
    }

    public function update(FishSpeciesRequest $request, FishSpecies $fishSpecies)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        $fishSpecies->update($data);
        return back()->with('success', 'Spesies ikan berhasil diperbarui!');
    }

    public function destroy(FishSpecies $fishSpecies)
    {
        $fishSpecies->delete();
        return back()->with('success', 'Spesies ikan berhasil dihapus!');
    }
}