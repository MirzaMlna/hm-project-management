<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemImport;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $categories = ItemCategory::orderBy('category')->get();

        $selectedCategory = $request->get('category'); // kategori terpilih
        $items = Item::with('category')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->where('item_category_id', $selectedCategory);
            })
            ->orderBy('item_category_id')
            ->get();

        return view('items.index', compact('items', 'categories', 'selectedCategory'));
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

    // 🔹 NEW: Import Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ItemImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data barang berhasil diimport dari Excel.');
    }
}
