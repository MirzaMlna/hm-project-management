<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemOut;
use App\Models\ItemStock;
use App\Models\DevelopmentPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ItemOutController extends Controller
{
    /**
     * Tampilkan daftar barang keluar
     */
    public function index(Request $request)
    {
        $categories = ItemCategory::orderBy('category')->get();
        $points = DevelopmentPoint::orderBy('development_point')->get();

        // Default bulan ini
        $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));

        $itemOuts = ItemOut::with(['item', 'developmentPoint'])
            ->whereYear('date_out', substr($selectedMonth, 0, 4))
            ->whereMonth('date_out', substr($selectedMonth, 5, 2))
            ->orderBy('date_out', 'desc')
            ->paginate(10);

        return view('item-outs.index', compact('itemOuts', 'categories', 'points', 'selectedMonth'));
    }

    /**
     * Simpan barang keluar
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'development_point_id' => 'required|exists:development_points,id',
            'quantity' => 'required|integer|min:1',
            'date_out' => 'required|date',
            'note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            // Simpan barang keluar
            ItemOut::create([
                'item_id' => $request->item_id,
                'development_point_id' => $request->development_point_id,
                'quantity' => $request->quantity,
                'date_out' => $request->date_out,
                'note' => $request->note,
            ]);

            // Kurangi stok
            $stock = ItemStock::where('item_id', $request->item_id)->first();
            if ($stock) {
                $stock->decrement('current_stock', $request->quantity);
                $stock->update(['last_updated' => Carbon::now()]);
            }
        });

        return redirect()->back()->with('success', 'Barang keluar berhasil disimpan dan stok diperbarui.');
    }

    /**
     * Hapus data barang keluar
     */
    public function destroy(ItemOut $itemOut)
    {
        DB::transaction(function () use ($itemOut) {
            // Kembalikan stok
            $stock = ItemStock::where('item_id', $itemOut->item_id)->first();
            if ($stock) {
                $stock->increment('current_stock', $itemOut->quantity);
                $stock->update(['last_updated' => Carbon::now()]);
            }

            $itemOut->delete();
        });

        return redirect()->back()->with('success', 'Data barang keluar berhasil dihapus dan stok disesuaikan.');
    }

    /**
     * Ambil item berdasarkan kategori (untuk dropdown dinamis)
     */
    public function getItemsByCategory($id)
    {
        $items = Item::where('item_category_id', $id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($items);
    }
}
