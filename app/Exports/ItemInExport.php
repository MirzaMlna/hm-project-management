<?php

namespace App\Exports;

use App\Models\ItemSupplier;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

class ItemInExport implements WithMultipleSheets
{
    protected $selectedMonth;

    public function __construct($selectedMonth)
    {
        $this->selectedMonth = $selectedMonth;
    }

    public function sheets(): array
    {
        $sheets = [];

        // ✅ Sheet pertama: semua barang masuk
        $sheets[] = new ItemInDetailSheet($this->selectedMonth);

        // ✅ Tambahkan 1 sheet per supplier
        $suppliers = ItemSupplier::orderBy('supplier')->get();
        foreach ($suppliers as $supplier) {
            $sheets[] = new ItemInPerSupplierSheet($this->selectedMonth, $supplier);
        }

        return $sheets;
    }
}
