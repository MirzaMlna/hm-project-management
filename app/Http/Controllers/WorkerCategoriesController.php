<?php

namespace App\Http\Controllers;

use App\Models\WorkerCategories;
use Illuminate\Http\Request;

class WorkerCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = WorkerCategories::latest()->paginate(10);
        return view('worker-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('worker-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
        ]);

        WorkerCategories::create($validated);

        return redirect()->route('worker-categories.index')
            ->with('success', 'Kategori tukang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = WorkerCategories::findOrFail($id);
        return view('worker-categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = WorkerCategories::findOrFail($id);
        return view('worker-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
        ]);

        $category = WorkerCategories::findOrFail($id);
        $category->update($validated);

        return redirect()->route('worker-categories.index')
            ->with('success', 'Kategori tukang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = WorkerCategories::findOrFail($id);
        $category->delete();

        return redirect()->route('worker-categories.index')
            ->with('success', 'Kategori tukang berhasil dihapus.');
    }
}
