<?php

namespace App\Exports;

use App\Models\ItemStock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ItemStockExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function collection(): Collection
    {
        $stocks = ItemStock::with('item.category')->get();

        return $stocks->map(function ($stock, $index) {
            return [
                'No' => $index + 1,
                'Kategori' => $stock->item->category->category ?? '-',
                'Nama Barang' => $stock->item->name ?? '-',
                'Satuan' => $stock->item->unit ?? '-',
                'Stok Saat Ini' => $stock->current_stock,
                'Stok Minimal' => $stock->minimum_stock ?? '-',
                'Terakhir Diperbarui' => $stock->last_updated
                    ? Carbon::parse($stock->last_updated)->translatedFormat('d F Y H:i')
                    : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Kategori',
            'Nama Barang',
            'Satuan',
            'Stok Saat Ini',
            'Stok Minimal',
            'Terakhir Diperbarui',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFDDEEFF');

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Stok Barang');
    }

    public function title(): string
    {
        return 'Stok Barang';
    }
}
