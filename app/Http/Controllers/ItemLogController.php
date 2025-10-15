<?php

namespace App\Http\Controllers;

use App\Exports\ItemLogExport;
use App\Models\ItemIn;
use App\Models\ItemOut;
use App\Models\ItemStock;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;

class ItemLogController extends Controller
{
    public function index(Request $request)
    {
        // 🔹 Ambil filter bulan (default = bulan sekarang)
        $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));

        $startOfMonth = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $endOfMonth   = Carbon::parse($selectedMonth . '-01')->endOfMonth();

        // 🔹 Barang Masuk
        $ins = ItemIn::with(['item.category'])
            ->whereBetween('purchase_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->map(function ($row) {
                $item = optional($row->item);
                $category = optional($item->category);

                return [
                    'item_id'       => $row->item_id,
                    'kategori'      => $category->category ?? '-',
                    'jenis'         => $item->name ?? 'Barang Dihapus',
                    'tanggal'       => $row->purchase_date,
                    'created_at'    => $row->created_at,
                    'barang_masuk'  => (int) $row->quantity,
                    'barang_keluar' => 0,
                    'tipe'          => 'in',
                ];
            })
            ->values()
            ->toArray();

        $outs = ItemOut::with(['item.category'])
            ->whereBetween('date_out', [$startOfMonth, $endOfMonth])
            ->get()
            ->map(function ($row) {
                $item = optional($row->item);
                $category = optional($item->category);

                return [
                    'item_id'       => $row->item_id,
                    'kategori'      => $category->category ?? '-',
                    'jenis'         => $item->name ?? 'Barang Dihapus',
                    'tanggal'       => $row->date_out,
                    'created_at'    => $row->created_at,
                    'barang_masuk'  => 0,
                    'barang_keluar' => (int) $row->quantity,
                    'tipe'          => 'out',
                ];
            })
            ->values()
            ->toArray();

        $rows = collect($ins)->merge($outs)->values();

        $rowsByItem = $rows->groupBy('item_id')->flatMap(function (Collection $group, $itemId) {
            $sorted = $group->sort(function ($a, $b) {
                if ($a['tanggal'] != $b['tanggal']) return strcmp($a['tanggal'], $b['tanggal']);
                if (($a['created_at'] ?? null) != ($b['created_at'] ?? null))
                    return strcmp(($a['created_at'] ?? ''), ($b['created_at'] ?? ''));
                return $a['tipe'] === 'in' ? -1 : 1;
            })->values();

            $totalIn  = $sorted->sum('barang_masuk');
            $totalOut = $sorted->sum('barang_keluar');

            $currentStock = (int) optional(ItemStock::where('item_id', $itemId)->first())->current_stock;
            $openingStock = $currentStock - ($totalIn - $totalOut);
            $running = $openingStock;

            return $sorted->map(function ($r) use (&$running) {
                if ($r['barang_masuk'] > 0) $running += $r['barang_masuk'];
                if ($r['barang_keluar'] > 0) $running -= $r['barang_keluar'];
                if ($running < 0) $running = 0;

                return [
                    'kategori'      => $r['kategori'],
                    'jenis'         => $r['jenis'],
                    'tanggal'       => $r['tanggal'],
                    'created_at'    => $r['created_at'], // ✅ tambahkan ini
                    'barang_masuk'  => $r['barang_masuk'],
                    'barang_keluar' => $r['barang_keluar'],
                    'stok'          => $running,
                ];
            });
        });

        // 🔹 Urutkan berdasarkan tanggal dan waktu pembuatan (terbaru di atas)
        $logs = $rowsByItem->sortByDesc(function ($item) {
            return $item['tanggal'] . ' ' . ($item['created_at'] ?? '0000-00-00 00:00:00');
        })->values();

        // 🔹 Pagination
        $perPage = 20;
        $page = request()->get('page', 1);
        $paginated = new LengthAwarePaginator(
            $logs->forPage($page, $perPage),
            $logs->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // 🔹 Daftar bulan
        $months = collect(range(0, 11))->map(function ($i) {
            $date = Carbon::now()->subMonths($i);
            return [
                'value' => $date->format('Y-m'),
                'label' => $date->translatedFormat('F Y'),
            ];
        });

        return view('item-logs.index', [
            'logs' => $paginated,
            'months' => $months,
            'selectedMonth' => $selectedMonth,
        ]);
    }


    // 🔹 Export ke Excel
    public function export(Request $request)
    {
        $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $filename = 'Log Barang - ' . Carbon::parse($selectedMonth . '-01')->translatedFormat('F Y') . '.xlsx';

        return Excel::download(new ItemLogExport($selectedMonth), $filename);
    }
}
