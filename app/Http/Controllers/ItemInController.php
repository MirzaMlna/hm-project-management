<?php

namespace App\Http\Controllers;

use App\Exports\ItemInExport;
use App\Models\ItemCategory;
use App\Models\ItemIn;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\ItemSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ItemInController extends Controller
{

    public function index(Request $request)
    {
        $items = Item::orderBy('name')->get();
        $suppliers = ItemSupplier::orderBy('supplier')->get();
        $categories = ItemCategory::orderBy('category')->get();

        // Ambil bulan yang dipilih dari query (?month=2025-10)
        $selectedMonth = $request->get('month', now()->format('Y-m')); // default bulan ini

        // Ambil data berdasarkan bulan yang dipilih
        $itemIns = ItemIn::with(['item', 'supplier'])
            ->whereYear('purchase_date', substr($selectedMonth, 0, 4))
            ->whereMonth('purchase_date', substr($selectedMonth, 5, 2))
            ->orderBy('purchase_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('item-ins.index', compact('itemIns', 'items', 'suppliers', 'categories', 'selectedMonth'));
    }




    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:item_suppliers,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'recipt_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'item_in_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // ✅ validasi baru
            'note' => 'nullable|string',
        ]);

        $reciptPath = null;
        if ($request->hasFile('recipt_photo')) {
            $reciptPath = $request->file('recipt_photo')->store('receipts', 'public');
        }

        $itemInPath = null;
        if ($request->hasFile('item_in_photo')) {
            $itemInPath = $request->file('item_in_photo')->store('item_in_photos', 'public');
        }

        $total = $request->quantity * $request->unit_price;

        $itemIn = ItemIn::create([
            'item_id' => $request->item_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_price' => $total,
            'purchase_date' => $request->purchase_date,
            'recipt_photo' => $reciptPath,
            'item_in_photo' => $itemInPath, // ✅ simpan ke database
            'note' => $request->note,
        ]);

        // Update stok otomatis
        $stock = ItemStock::firstOrCreate(
            ['item_id' => $request->item_id],
            ['current_stock' => 0, 'minimum_stock' => 0, 'last_updated' => Carbon::now()]
        );
        $stock->increment('current_stock', $request->quantity);
        $stock->update(['last_updated' => Carbon::now()]);

        return redirect()->back()->with('success', 'Barang masuk berhasil disimpan dan stok diperbarui.');
    }

    public function destroy(ItemIn $itemIn)
    {
        if ($itemIn->recipt_photo && Storage::disk('public')->exists($itemIn->recipt_photo)) {
            Storage::disk('public')->delete($itemIn->recipt_photo);
        }
        if ($itemIn->item_in_photo && Storage::disk('public')->exists($itemIn->item_in_photo)) {
            Storage::disk('public')->delete($itemIn->item_in_photo);
        }
        // Kurangi stok
        $stock = ItemStock::where('item_id', $itemIn->item_id)->first();
        if ($stock && $stock->current_stock >= $itemIn->quantity) {
            $stock->decrement('current_stock', $itemIn->quantity);
            $stock->update(['last_updated' => Carbon::now()]);
        }

        $itemIn->delete();
        return redirect()->back()->with('success', 'Data barang masuk berhasil dihapus dan stok disesuaikan.');
    }

    public function getItemsByCategory($id)
    {
        $items = Item::where('item_category_id', $id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($items);
    }

    public function export(Request $request)
    {
        $selectedMonth = $request->get('month', now()->format('Y-m'));
        $fileName = 'Barang_Masuk_' . $selectedMonth . '.xlsx';

        return Excel::download(new ItemInExport($selectedMonth), $fileName);
    }
}
