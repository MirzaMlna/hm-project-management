<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    public function index()
    {
        $categories = ItemCategory::orderBy('id', 'desc')->get();
        return view('item-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100|unique:item_categories,category',
        ]);

        ItemCategory::create([
            'category' => $request->category,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, ItemCategory $itemCategory)
    {
        $request->validate([
            'category' => 'required|string|max:100|unique:item_categories,category,' . $itemCategory->id,
        ]);

        $itemCategory->update([
            'category' => $request->category,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(ItemCategory $itemCategory)
    {
        $itemCategory->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
