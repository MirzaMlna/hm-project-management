<?php

namespace App\Exports;

use App\Models\ItemIn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Carbon\Carbon;

class ItemInDetailSheet implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithTitle
{
    protected $selectedMonth;

    public function __construct($selectedMonth)
    {
        $this->selectedMonth = $selectedMonth;
    }

    public function title(): string
    {
        return 'Daftar Barang Masuk';
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function collection()
    {
        $start = Carbon::parse($this->selectedMonth . '-01')->startOfMonth();
        $end   = Carbon::parse($this->selectedMonth . '-01')->endOfMonth();

        return ItemIn::with(['item.category', 'supplier'])
            ->whereBetween('purchase_date', [$start, $end])
            ->orderBy('purchase_date', 'asc')
            ->get()
            ->map(function ($in) {
                return [
                    'Tanggal Pembelian' => Carbon::parse($in->purchase_date)->format('d/m/Y'),
                    'Kategori'          => $in->item->category->category ?? '-',
                    'Jenis Barang'      => $in->item->name ?? '-',
                    'Supplier'          => $in->supplier->supplier ?? '-',
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
            'Supplier',
            'Jumlah',
            'Harga Satuan (Rp)',
            'Total Harga (Rp)',
            'Catatan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // 🔹 Judul utama
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');

        $monthName = Carbon::parse($this->selectedMonth . '-01')->translatedFormat('F Y');
        $sheet->setCellValue('A1', 'LAPORAN PEMBELIAN BARANG GUDANG HM COMPANY');
        $sheet->setCellValue('A2', 'PROYEK JL. LINGKAR DALAM SELATAN');
        $sheet->setCellValue('A3', 'PERIODE ' . strtoupper($monthName));

        $sheet->getStyle('A1:A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // 🔹 Header tabel
        $sheet->getStyle('A5:H5')->applyFromArray([
            'font' => ['bold' => true, 'name' => 'Times New Roman', 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '60A5FA']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // 🔹 Isi tabel
        $dataStartRow = 6;
        $dataEndRow = $sheet->getHighestRow();

        $sheet->getStyle("A{$dataStartRow}:H{$dataEndRow}")->applyFromArray([
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

        // 🔹 Format angka untuk kolom harga
        $sheet->getStyle("F{$dataStartRow}:G{$dataEndRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("F{$dataStartRow}:G{$dataEndRow}")
            ->getNumberFormat()
            ->setFormatCode('"Rp" #,##0');

        // 🔹 Baris total harga
        $totalRow = $dataEndRow + 1;
        $sheet->mergeCells("A{$totalRow}:F{$totalRow}");
        $sheet->setCellValue("A{$totalRow}", 'JUMLAH HARGA');
        $sheet->setCellValue("G{$totalRow}", "=SUM(G{$dataStartRow}:G{$dataEndRow})");

        $sheet->getStyle("A{$totalRow}:H{$totalRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'name' => 'Times New Roman',
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DBEAFE'], // biru muda pucat
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        // Kolom total harga rata kanan & format Rp
        $sheet->getStyle("G{$totalRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("G{$totalRow}")
            ->getNumberFormat()
            ->setFormatCode('"Rp" #,##0');

        // 🔹 Lebar kolom otomatis
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 🔹 Orientasi cetak
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

        return [];
    }
}
