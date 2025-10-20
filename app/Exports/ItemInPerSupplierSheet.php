<?php

namespace App\Exports;

use App\Models\ItemIn;
use App\Models\ItemSupplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Carbon\Carbon;

class ItemInPerSupplierSheet implements FromCollection, WithHeadings, WithStyles, WithTitle, WithCustomStartCell
{
    protected $selectedMonth;
    protected $supplier;

    public function __construct($selectedMonth, ItemSupplier $supplier)
    {
        $this->selectedMonth = $selectedMonth;
        $this->supplier = $supplier;
    }

    public function title(): string
    {
        // Nama sheet pakai nama supplier (maksimal 31 karakter)
        return mb_substr($this->supplier->supplier, 0, 31);
    }

    public function startCell(): string
    {
        return 'A6'; // mulai di baris 6 karena baris 1–5 untuk heading
    }

    public function collection()
    {
        $start = Carbon::parse($this->selectedMonth . '-01')->startOfMonth();
        $end   = Carbon::parse($this->selectedMonth . '-01')->endOfMonth();

        return ItemIn::with(['item.category', 'supplier'])
            ->where('supplier_id', $this->supplier->id)
            ->whereBetween('purchase_date', [$start, $end])
            ->orderBy('purchase_date', 'asc')
            ->get()
            ->map(function ($in) {
                return [
                    'Tanggal Pembelian' => Carbon::parse($in->purchase_date)->format('d/m/Y'),
                    'Kategori'          => $in->item->category->category ?? '-',
                    'Jenis Barang'      => $in->item->name ?? '-',
                    'Jumlah'            => $in->quantity,
                    'Harga Satuan (Rp)' => $in->unit_price,
                    'Total Harga (Rp)'  => $in->total_price,
                    'Catatan'           => $in->note ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal Pembelian',
            'Kategori',
            'Jenis Barang',
            'Jumlah',
            'Harga Satuan (Rp)',
            'Total Harga (Rp)',
            'Catatan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // 🔹 Heading utama (judul laporan)
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $sheet->mergeCells('A4:G4');

        $monthName = Carbon::parse($this->selectedMonth . '-01')->translatedFormat('F Y');

        $sheet->setCellValue('A1', 'LAPORAN PEMBELIAN BARANG GUDANG HM COMPANY');
        $sheet->setCellValue('A2', 'PROYEK JL. LINGKAR DALAM SELATAN');
        $sheet->setCellValue('A3', 'SUPPLIER: ' . strtoupper($this->supplier->supplier));
        $sheet->setCellValue('A4', 'PERIODE ' . strtoupper($monthName));

        $sheet->getStyle('A1:A4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'name' => 'Times New Roman',
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // 🔹 Header tabel (baris 6)
        $sheet->getStyle('A6:G6')->applyFromArray([
            'font' => [
                'bold' => true,
                'name' => 'Times New Roman',
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '60A5FA'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        // 🔹 Isi tabel
        $dataStartRow = 7;
        $dataEndRow = $sheet->getHighestRow();

        $sheet->getStyle("A{$dataStartRow}:G{$dataEndRow}")->applyFromArray([
            'font' => [
                'name' => 'Times New Roman',
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        // 🔹 Format angka (Rp)
        $sheet->getStyle("E{$dataStartRow}:F{$dataEndRow}")
            ->getNumberFormat()
            ->setFormatCode('"Rp" #,##0');

        // 🔹 Total harga di bawah tabel
        $totalRow = $dataEndRow + 1;
        $sheet->mergeCells("A{$totalRow}:E{$totalRow}");
        $sheet->setCellValue("A{$totalRow}", 'TOTAL PEMBELIAN');
        $sheet->setCellValue("F{$totalRow}", "=SUM(F{$dataStartRow}:F{$dataEndRow})");

        $sheet->getStyle("A{$totalRow}:G{$totalRow}")->applyFromArray([
            'font' => ['bold' => true, 'name' => 'Times New Roman', 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $sheet->getStyle("F{$totalRow}")
            ->getNumberFormat()->setFormatCode('"Rp" #,##0');

        // 🔹 Lebar kolom otomatis
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 🔹 Atur halaman (landscape)
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

        return [];
    }
}
