<?php

namespace App\Exports;

use App\Models\ItemCategory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class ItemStockExport implements FromCollection, WithStyles, WithEvents
{
    protected $row = 1;

    public function collection(): Collection
    {
        return collect([]);
    }

    public function styles(Worksheet $sheet)
    {
        // Styling utama dilakukan di AfterSheet
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // === HEADER UTAMA ===
                $sheet->mergeCells('A1:G1');
                $sheet->setCellValue('A1', 'DATA STOK GUDANG HM COMPANY');
                $sheet->mergeCells('A2:G2');
                $sheet->setCellValue('A2', 'PROYEK JL.LINGKAR DALAM SELATAN, BANJARMASIN');
                $sheet->mergeCells('A3:G3');
                $sheet->setCellValue('A3', '(' . now()->translatedFormat('d F Y H:i') . ')');

                $sheet->getStyle('A1:A3')->applyFromArray([
                    'font' => [
                        'name' => 'Times New Roman',
                        'bold' => true,
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $this->row = 5;
                $categories = ItemCategory::with(['items.stock'])->orderBy('category')->get();

                foreach ($categories as $category) {
                    // === KATEGORI ===
                    $sheet->mergeCells("A{$this->row}:G{$this->row}");
                    $sheet->setCellValue("A{$this->row}", strtoupper($category->category));
                    $sheet->getStyle("A{$this->row}:G{$this->row}")->applyFromArray([
                        'font' => [
                            'name' => 'Times New Roman',
                            'bold' => true,
                            'size' => 11,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFDDEBF7'],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                    ]);
                    $this->row++;

                    // === HEADING KOLOM ===
                    $headers = [
                        'No',
                        'Jenis Barang',
                        'Satuan',
                        'Stok',
                        'Stok Minimal',
                        'Status Stok',
                        'Terakhir Diperbarui',
                    ];

                    $sheet->fromArray([$headers], null, "A{$this->row}");
                    $sheet->getStyle("A{$this->row}:G{$this->row}")->applyFromArray([
                        'font' => [
                            'name' => 'Times New Roman',
                            'bold' => true,
                            'size' => 11,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFBDD7EE'],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                    ]);
                    $this->row++;

                    // === DATA ===
                    $no = 1;
                    foreach ($category->items as $item) {
                        $stock = $item->stock;
                        $current = $stock->current_stock ?? 0;
                        $minimal = $stock->minimum_stock ?? 0;

                        // Tentukan status stok
                        $status = '-';
                        if ($minimal > 0) {
                            if ($current < $minimal) {
                                $status = 'Kurang';
                            } elseif ($current == $minimal) {
                                $status = 'Batas Aman';
                            } else {
                                $status = 'Lebih';
                            }
                        }

                        $sheet->fromArray([
                            [
                                $no++,
                                $item->name ?? '-',
                                $item->unit ?? '-',
                                $current,
                                $minimal ?: '-',
                                $status,
                                $stock && $stock->last_updated
                                    ? Carbon::parse($stock->last_updated)->translatedFormat('d F Y')
                                    : '-',
                            ]
                        ], null, "A{$this->row}");

                        // Style baris data
                        $sheet->getStyle("A{$this->row}:G{$this->row}")->applyFromArray([
                            'font' => [
                                'name' => 'Times New Roman',
                                'size' => 11,
                            ],
                            'alignment' => [
                                'vertical'   => Alignment::VERTICAL_CENTER,
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['argb' => 'FF000000'],
                                ],
                            ],
                        ]);

                        // Nama barang rata kiri
                        $sheet->getStyle("B{$this->row}")
                            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                        $this->row++;
                    }

                    // Spasi antar kategori
                    $this->row += 2;
                }

                // === FORMAT TAMBAHAN ===
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                for ($r = 1; $r <= $this->row; $r++) {
                    $sheet->getRowDimension($r)->setRowHeight(-1);
                }

                $sheet->freezePane('A5');

                // === PENGATURAN CETAK / PRINT ===
                $pageSetup = $sheet->getPageSetup();
                $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE); // Landscape
                $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);
                $pageSetup->setFitToWidth(1); // Fit all columns to one page
                $pageSetup->setFitToHeight(0); // Unlimited height (panjang menyesuaikan)

                // Margin & scaling tambahan
                $sheet->getPageMargins()->setTop(0.4);
                $sheet->getPageMargins()->setRight(0.3);
                $sheet->getPageMargins()->setLeft(0.3);
                $sheet->getPageMargins()->setBottom(0.4);

                // Center horizontally when printed
                $sheet->getPageSetup()->setHorizontalCentered(true);
            },
        ];
    }
}
