<?php

namespace App\Http\Controllers;

use App\Models\WorkerCategory;
use Illuminate\Http\Request;

class WorkerCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = WorkerCategory::withCount('workers')->get();
        return view('worker-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
        ]);

        WorkerCategory::create($validated);

        return redirect()->route('worker-categories.index')
            ->with('success', 'Kategori tukang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
        ]);

        $category = WorkerCategory::findOrFail($id);
        $category->update($validated);

        return redirect()->route('worker-categories.index')
            ->with('success', 'Kategori tukang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = WorkerCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('worker-categories.index')
            ->with('success', 'Kategori tukang berhasil dihapus.');
    }
}
