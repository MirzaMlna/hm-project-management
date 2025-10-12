<?php

namespace App\Http\Controllers;

use App\Exports\ItemStockExport;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemStock;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ItemStockController extends Controller
{
    public function index()
    {
        $stocks = ItemStock::with('item')->orderBy('id', 'desc')->paginate(10);
        $items = Item::orderBy('name')->get();
        $categories = ItemCategory::orderBy('category')->get(); // 🔹 ditambahkan
        return view('item-stocks.index', compact('stocks', 'items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'nullable|integer|min:0',
        ]);

        ItemStock::updateOrCreate(
            ['item_id' => $request->item_id],
            [
                'current_stock' => $request->current_stock,
                'minimum_stock' => $request->minimum_stock,
                'last_updated' => Carbon::now(),
            ]
        );

        return redirect()->back()->with('success', 'Stok barang berhasil disimpan.');
    }

    public function update(Request $request, ItemStock $itemStock)
    {
        $request->validate([
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'nullable|integer|min:0',
        ]);

        $itemStock->update([
            'current_stock' => $request->current_stock,
            'minimum_stock' => $request->minimum_stock,
            'last_updated' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy(ItemStock $itemStock)
    {
        $itemStock->delete();
        return redirect()->back()->with('success', 'Data stok berhasil dihapus.');
    }
    public function getByCategory($id)
    {
        $items = Item::where('item_category_id', $id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($items);
    }

    public function export()
    {
        $fileName = 'Stok_Gudang_' . now()->format('d-m-Y_H-i') . '.xlsx';
        return Excel::download(new ItemStockExport(), $fileName);
    }
}
