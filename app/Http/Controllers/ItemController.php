<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index()
    {
        $categories = ItemCategory::orderBy('category')->get();
        $items = Item::with('category')->orderBy('item_category_id')->get();

        return view('items.index', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_category_id' => 'required|exists:item_categories,id',
            'name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
            'photo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $code = 'BRG' . str_pad(Item::count() + 1, 3, '0', STR_PAD_LEFT);
        $photoPath = $request->file('photo') ? $request->file('photo')->store('items', 'public') : null;

        Item::create([
            'item_category_id' => $request->item_category_id,
            'code' => $code,
            'name' => $request->name,
            'unit' => $request->unit,
            'photo' => $photoPath,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'item_category_id' => 'required|exists:item_categories,id',
            'name' => 'required|string|max:100',
            'unit' => 'required|string|max:20',
            'photo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
            $item->photo = $request->file('photo')->store('items', 'public');
        }

        $item->update([
            'item_category_id' => $request->item_category_id,
            'name' => $request->name,
            'unit' => $request->unit,
            'photo' => $item->photo,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        if ($item->photo) {
            Storage::disk('public')->delete($item->photo);
        }
        $item->delete();
        return redirect()->back()->with('success', 'Barang berhasil dihapus.');
    }
}
