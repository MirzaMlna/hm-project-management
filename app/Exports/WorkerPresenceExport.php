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

class WorkerPresenceExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle
{
    protected $rows;
    protected $period;      // array tanggal (format '01-Sep', ...)
    protected $dateRange;
    protected $categoryName;

    public function __construct(array $rows, array $period, string $dateRange, string $categoryName)
    {
        $this->rows = $rows;
        $this->period = $period;
        $this->dateRange = $dateRange;
        $this->categoryName = $categoryName;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        $baseHead = ['No', 'Kode', 'Nama', 'Upah Harian'];
        $presencesDetails = array_fill(0, count($this->period), '');
        $lastHead = ['Total', 'DLA', 'KLL', 'LM', 'No'];

        $row1 = array_merge($baseHead, $presencesDetails, $lastHead);
        $row2 = array_merge($baseHead, $this->period, $lastHead);

        return [$row1, $row2];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set default font Times New Roman untuk seluruh sheet
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
                $sheet->getParent()->getDefaultStyle()->getFont()->setSize(11);

                // sisip 4 baris judul di atas
                $sheet->insertNewRowBefore(1, 4);

                $periodCount = count($this->period);
                $startColIndex = 5; // kolom E
                $endColIndex = $startColIndex + max(0, $periodCount - 1);

                $summaryColsCount = 5;
                $summaryStartIndex = $endColIndex + 1;
                $summaryEndIndex = $summaryStartIndex + $summaryColsCount - 1;

                $lastCol = Coordinate::stringFromColumnIndex($summaryEndIndex);

                // ===== Judul atas =====
                $sheet->mergeCells("A1:{$lastCol}1")->setCellValue('A1', 'ABSEN PEKERJA HARIAN HM COMPANY');
                $sheet->mergeCells("A2:{$lastCol}2")->setCellValue('A2', 'PROYEK JL. LINGKAR DALAM SELATAN, BANJARMASIN');
                $sheet->mergeCells("A3:{$lastCol}3")->setCellValue('A3', $this->dateRange);
                $sheet->mergeCells("A4:{$lastCol}4")->setCellValue('A4', $this->categoryName);

                $sheet->getStyle("A1:{$lastCol}4")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ===== Merge header kiri (No..Upah) ke bawah =====
                for ($i = 1; $i <= 4; $i++) {
                    $col = Coordinate::stringFromColumnIndex($i);
                    $sheet->mergeCells("{$col}5:{$col}6");
                }

                // ===== Merge kolom summary (Total..LM) =====
                for ($i = $summaryStartIndex; $i <= $summaryEndIndex; $i++) {
                    $col = Coordinate::stringFromColumnIndex($i);
                    $sheet->mergeCells("{$col}5:{$col}6");
                }

                // ===== Merge Uraian Hari/Tanggal =====
                $startCell = Coordinate::stringFromColumnIndex($startColIndex);
                $endCell   = Coordinate::stringFromColumnIndex($endColIndex);
                if ($periodCount > 0) {
                    $sheet->mergeCells("{$startCell}5:{$endCell}5")
                        ->setCellValue("{$startCell}5", "Uraian Hari/Tanggal");
                } else {
                    $sheet->setCellValue("{$startCell}5", "Uraian Hari/Tanggal");
                }

                // ===== Styling header =====
                $sheet->getStyle("A5:{$lastCol}6")->applyFromArray([
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
                        'startColor' => ['rgb' => 'D9E1F2'] // biru muda
                    ]
                ]);

                // ===== Styling data =====
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A7:{$lastCol}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ===== Format Rupiah di kolom Upah Harian =====
                $salaryCol = Coordinate::stringFromColumnIndex(4); // D
                $sheet->getStyle("{$salaryCol}7:{$salaryCol}{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"Rp"#,##0');

                // ===== Keterangan bawah =====
                $rowKeterangan = $highestRow + 2;
                $sheet->mergeCells("A{$rowKeterangan}:{$lastCol}{$rowKeterangan}")
                    ->setCellValue("A{$rowKeterangan}", "Keterangan :");
                $sheet->mergeCells("A" . ($rowKeterangan + 1) . ":{$lastCol}" . ($rowKeterangan + 1))
                    ->setCellValue("A" . ($rowKeterangan + 1), "Total : Jumlah Poin Kehadiran Tukang Harian");
                $sheet->mergeCells("A" . ($rowKeterangan + 2) . ":{$lastCol}" . ($rowKeterangan + 2))
                    ->setCellValue("A" . ($rowKeterangan + 2), "DLA : Datang Lebih Awal (Scan Presensi Pagi Sebelum Jam Presensi Pagi Dimulai)");
                $sheet->mergeCells("A" . ($rowKeterangan + 3) . ":{$lastCol}" . ($rowKeterangan + 3))
                    ->setCellValue("A" . ($rowKeterangan + 3), "KLL : Kerja Lebih Lama (Scan Presensi Sore Setelah Jam Presensi Pulang Berakhir)");
                $sheet->mergeCells("A" . ($rowKeterangan + 4) . ":{$lastCol}" . ($rowKeterangan + 4))
                    ->setCellValue("A" . ($rowKeterangan + 4), "LM : Lembur Malam (Scan Presensi 3 Jam Setelah Jam Kerja)");

                // ===== Auto size semua kolom =====
                $highestColumn = $sheet->getHighestColumn();
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                }

                // hitung ulang width agar efek autosize masuk
                $sheet->calculateColumnWidths();
            },
        ];
    }

    public function title(): string
    {
        // Nama sheet sesuai nama kategori
        return $this->categoryName;
    }
}
