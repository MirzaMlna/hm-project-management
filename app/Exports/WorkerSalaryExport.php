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

class WorkerSalaryExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle
{
    protected $rows;
    protected $categoryName;

    public function __construct(array $rows, string $categoryName)
    {
        $this->rows = $rows;
        $this->categoryName = $categoryName;
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

                // Font default Times New Roman
                $sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(11);

                $highestRow = $sheet->getHighestRow();
                $highestCol = $sheet->getHighestColumn();

                // Styling header
                $sheet->getStyle("A1:{$highestCol}1")->applyFromArray([
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

                // Styling body
                $sheet->getStyle("A2:{$highestCol}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Format rupiah di kolom Upah & Gaji
                foreach ([4, 5, 6, 7, 8] as $colIndex) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                    $sheet->getStyle("{$colLetter}2:{$colLetter}{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('"Rp"#,##0');
                }
            }
        ];
    }

    public function title(): string
    {
        return 'GAJI ' . $this->categoryName;
    }
}
