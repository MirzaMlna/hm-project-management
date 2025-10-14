<?php

namespace App\Exports;

use App\Models\ItemIn;
use App\Models\ItemOut;
use App\Models\ItemStock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Carbon\Carbon;

class ItemLogExport implements FromCollection, WithHeadings, WithStyles
{
    protected $selectedMonth;

    public function __construct($selectedMonth)
    {
        $this->selectedMonth = $selectedMonth;
    }

    public function collection()
    {
        $startOfMonth = Carbon::parse($this->selectedMonth . '-01')->startOfMonth();
        $endOfMonth   = Carbon::parse($this->selectedMonth . '-01')->endOfMonth();

        $ins = ItemIn::with(['item.category'])
            ->whereBetween('purchase_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->map(function ($row) {
                return [
                    'item_id'       => $row->item_id,
                    'kategori'      => $row->item->category->category ?? '-',
                    'jenis'         => $row->item->name ?? '-',
                    'tanggal'       => Carbon::parse($row->purchase_date),
                    'barang_masuk'  => (int) $row->quantity,
                    'barang_keluar' => 0,
                ];
            });

        $outs = ItemOut::with(['item.category'])
            ->whereBetween('date_out', [$startOfMonth, $endOfMonth])
            ->get()
            ->map(function ($row) {
                return [
                    'item_id'       => $row->item_id,
                    'kategori'      => $row->item->category->category ?? '-',
                    'jenis'         => $row->item->name ?? '-',
                    'tanggal'       => Carbon::parse($row->date_out),
                    'barang_masuk'  => 0,
                    'barang_keluar' => (int) $row->quantity,
                ];
            });

        // Urutkan berdasarkan tanggal
        $logs = $ins->merge($outs)->sortBy('tanggal')->values();

        $initialStocks = ItemStock::pluck('current_stock', 'item_id');

        $runningStocks = [];
        $finalLogs = collect();

        foreach ($logs as $log) {
            $itemId = $log['item_id'];
            $currentStock = $runningStocks[$itemId] ?? ($initialStocks[$itemId] ?? 0);

            // Hitung stok baru
            $currentStock += $log['barang_masuk'];
            $currentStock -= $log['barang_keluar'];

            $runningStocks[$itemId] = $currentStock;

            $finalLogs->push([
                'kategori'      => $log['kategori'],
                'jenis'         => $log['jenis'],
                'tanggal'       => $log['tanggal']->translatedFormat('d F Y'),
                'barang_masuk'  => $log['barang_masuk'],
                'barang_keluar' => $log['barang_keluar'],
                'stok'          => $currentStock,
            ]);
        }

        // Tambah nomor urut
        $finalLogs = $finalLogs->map(function ($item, $index) {
            return array_merge(['no' => $index + 1], $item);
        });

        return new Collection($finalLogs);
    }

    public function headings(): array
    {
        $periode = strtoupper(Carbon::parse($this->selectedMonth . '-01')->translatedFormat('F Y'));

        return [
            ['LAPORAN PERGERAKAN BARANG GUDANG HM COMPANY'],
            ['PROYEK JL. LINGKAR DALAM SELATAN, BANJARMASIN'],
            ['PERIODE: ' . $periode],
            [],
            ['No', 'Kategori', 'Jenis Barang', 'Tanggal', 'Barang Masuk', 'Barang Keluar', 'Stok'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge title
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');

        // Font global
        $sheet->getParent()->getDefaultStyle()->getFont()
            ->setName('Times New Roman')
            ->setSize(11);

        // Title styling
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A1:A3')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // Header table
        $sheet->getStyle('A5:G5')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DCE6F1'], // biru muda
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        // Style isi tabel umum
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A5:G$highestRow")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        // Bold Jenis Barang, Masuk, Keluar
        $sheet->getStyle("C6:C$highestRow")->getFont()->setBold(true);
        $sheet->getStyle("E6:F$highestRow")->getFont()->setBold(true);

        // ✅ Conditional formatting warna hijau/merah
        for ($row = 6; $row <= $highestRow; $row++) {
            $masuk  = $sheet->getCell("E$row")->getValue();
            $keluar = $sheet->getCell("F$row")->getValue();

            if ($masuk > 0) {
                // Barang masuk = hijau
                $sheet->getStyle("E$row:G$row")->getFont()->getColor()->setRGB('008000'); // hijau
            } elseif ($keluar > 0) {
                // Barang keluar = merah
                $sheet->getStyle("E$row:G$row")->getFont()->getColor()->setRGB('FF0000'); // merah
            }
        }

        // Auto fit column
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Page setup
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
            ->setFitToPage(true)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        $sheet->getDefaultRowDimension()->setRowHeight(20);
    }
}
