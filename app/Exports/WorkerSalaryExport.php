<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class WorkerSalaryExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle
{
    protected $rows;
    protected $categoryName;
    protected $dateRange;

    public function __construct(array $rows, string $categoryName, string $dateRange)
    {
        $this->rows = $rows;
        $this->categoryName = $categoryName;
        $this->dateRange = $dateRange;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [[
            'No',
            'Kode',
            'Nama',
            'Upah Harian',
            'Bonus DLA',
            'Bonus KLL',
            'Bonus LM',
            'Total Gaji',
            'TTD',
            'Keterangan'
        ]];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // === FONT DEFAULT ===
                $sheet->getParent()->getDefaultStyle()->getFont()
                    ->setName('Times New Roman')->setSize(11);

                // === SISIPKAN JUDUL ===
                $sheet->insertNewRowBefore(1, 4);
                $highestCol = $sheet->getHighestColumn();
                $lastCol = $highestCol;

                // === HEADER ATAS ===
                $sheet->mergeCells("A1:{$lastCol}1")
                    ->setCellValue("A1", "LAPORAN PERHITUNGAN GAJI PEKERJA HARIAN HM COMPANY");
                $sheet->mergeCells("A2:{$lastCol}2")
                    ->setCellValue("A2", "PROYEK JL. LINGKAR DALAM SELATAN, BANJARMASIN");
                $sheet->mergeCells("A3:{$lastCol}3")
                    ->setCellValue("A3", $this->dateRange);
                $sheet->mergeCells("A4:{$lastCol}4")
                    ->setCellValue("A4", "Kategori: " . $this->categoryName);

                $sheet->getStyle("A1:{$lastCol}4")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // === HEADER TABEL ===
                $sheet->getStyle("A5:{$lastCol}5")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9E1F2']
                    ]
                ]);

                // === STYLING DATA ===
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A6:{$lastCol}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // === FORMAT RUPIAH ===
                foreach ([4, 5, 6, 7, 8] as $colIndex) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                    $sheet->getStyle("{$colLetter}6:{$colLetter}{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('"Rp"#,##0');
                }

                // === JUMLAH TOTAL ===
                $jumlahRow = $highestRow + 1;
                $sheet->mergeCells("A{$jumlahRow}:G{$jumlahRow}");
                $sheet->setCellValue("A{$jumlahRow}", "JUMLAH");
                $sheet->setCellValue("H{$jumlahRow}", "=SUM(H6:H{$highestRow})");

                $sheet->getStyle("A{$jumlahRow}:{$lastCol}{$jumlahRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFF2CC']
                    ]
                ]);
                $sheet->getStyle("H{$jumlahRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"Rp"#,##0');

                // === KETERANGAN BAWAH ===
                $rowKeterangan = $jumlahRow + 2;
                $sheet->mergeCells("A{$rowKeterangan}:{$lastCol}{$rowKeterangan}")
                    ->setCellValue("A{$rowKeterangan}", "Keterangan :");
                $sheet->mergeCells("A" . ($rowKeterangan + 1) . ":{$lastCol}" . ($rowKeterangan + 1))
                    ->setCellValue("A" . ($rowKeterangan + 1), "Upah Harian = Total Poin Kehadiran x Upah Harian");
                $sheet->mergeCells("A" . ($rowKeterangan + 2) . ":{$lastCol}" . ($rowKeterangan + 2))
                    ->setCellValue("A" . ($rowKeterangan + 2), "Bonus DLA = Total DLA x Nominal Bonus DLA");
                $sheet->mergeCells("A" . ($rowKeterangan + 3) . ":{$lastCol}" . ($rowKeterangan + 3))
                    ->setCellValue("A" . ($rowKeterangan + 3), "Bonus KLL = Total KLL x Nominal Bonus KLL");
                $sheet->mergeCells("A" . ($rowKeterangan + 4) . ":{$lastCol}" . ($rowKeterangan + 4))
                    ->setCellValue("A" . ($rowKeterangan + 4), "Bonus LM = Total LM x Upah Harian");
                $sheet->mergeCells("A" . ($rowKeterangan + 5) . ":{$lastCol}" . ($rowKeterangan + 5))
                    ->setCellValue("A" . ($rowKeterangan + 5), "Total Gaji = Upah Harian + Bonus DLA + Bonus KLL + Bonus LM");

                // === AUTO SIZE KOLOM ===
                $highestColumn = $sheet->getHighestColumn();
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                }

                // === ATUR TINGGI BARIS ===
                for ($r = 1; $r <= $sheet->getHighestRow(); $r++) {
                    // Baris judul & header sedikit lebih tinggi
                    if ($r <= 5) {
                        $sheet->getRowDimension($r)->setRowHeight(25);
                    } else {
                        $sheet->getRowDimension($r)->setRowHeight(22);
                    }
                }

                // === PRINT SETTINGS ===
                $pageSetup = $sheet->getPageSetup();
                $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);
                $pageSetup->setFitToWidth(1);
                $pageSetup->setFitToHeight(0);
                $pageSetup->setHorizontalCentered(true);

                // === MARGIN PRINT ===
                $margins = $sheet->getPageMargins();
                $margins->setTop(0.4);
                $margins->setBottom(0.4);
                $margins->setLeft(0.3);
                $margins->setRight(0.3);
            }
        ];
    }

    public function title(): string
    {
        return 'GAJI ' . $this->categoryName;
    }
}
