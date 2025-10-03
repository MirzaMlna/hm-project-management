<?php

namespace App\Imports;

use App\Models\Worker;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

class WorkersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Lewati baris header
        $rows->skip(1)->each(function ($row) {
            Worker::updateOrCreate(
                ['code' => $row[2]], // unik berdasarkan kode
                [
                    'worker_category_id' => $row[1], // ✅ langsung pakai ID dari file Excel
                    'name'         => $row[0],
                    'daily_salary' => str_replace(['Rp', '.', ','], '', $row[3]), // bersihkan format
                    'phone'        => $row[4] ?? null,
                    'birth_date'   => $row[5] ? Carbon::parse($row[5]) : null,
                    'is_active'    => true,
                ]
            );
        });
    }
}
