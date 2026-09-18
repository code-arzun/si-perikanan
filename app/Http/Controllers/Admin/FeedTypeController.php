<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedType;
use App\Http\Requests\Admin\FeedTypeRequest;

class FeedTypeController extends Controller
{
    public function index()
    {
        $feedTypes = FeedType::latest()->paginate(15);
        return view('admin.master.feed_types.index', compact('feedTypes'));
    }

    public function store(FeedTypeRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        FeedType::create($data);

        return back()->with('success', 'Jenis pakan berhasil ditambahkan!');
    }

    public function update(FeedTypeRequest $request, FeedType $feedType)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        $feedType->update($data);

        return back()->with('success', 'Jenis pakan berhasil diperbarui!');
    }

    public function destroy(FeedType $feedType)
    {
        $feedType->delete();

        return back()->with('success', 'Jenis pakan berhasil dihapus!');
    }
}