<?php

namespace App\Http\Controllers;

use App\Models\ItemSupplier;
use Illuminate\Http\Request;

class ItemSupplierController extends Controller
{
    /**
     * Tampilkan daftar semua pemasok.
     */
    public function index()
    {
        // ambil 10 pemasok per halaman
        $suppliers = ItemSupplier::orderBy('supplier')->paginate(10);

        return view('item-suppliers.index', compact('suppliers'));
    }


    /**
     * Simpan pemasok baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // generate kode otomatis SUP001, SUP002, ...
        $last = ItemSupplier::latest('id')->first();
        $nextCode = 'SUP' . str_pad(($last?->id ?? 0) + 1, 3, '0', STR_PAD_LEFT);

        ItemSupplier::create([
            'code' => $nextCode,
            'supplier' => $request->supplier,
            'phone' => $request->phone,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Pemasok berhasil ditambahkan.');
    }

    /**
     * Update data pemasok.
     */
    public function update(Request $request, ItemSupplier $item_supplier)
    {
        $request->validate([
            'supplier' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $item_supplier->update($request->only('supplier', 'phone', 'address', 'description'));

        return redirect()->back()->with('success', 'Data pemasok berhasil diperbarui.');
    }

    /**
     * Hapus pemasok.
     */
    public function destroy(ItemSupplier $item_supplier)
    {
        $item_supplier->delete();
        return redirect()->back()->with('success', 'Pemasok berhasil dihapus.');
    }
}
