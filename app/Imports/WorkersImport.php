<?php

namespace App\Imports;

use App\Models\Worker;
use App\Models\WorkerCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

class WorkersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Lewati baris header
        $rows->skip(1)->each(function ($row) {

            $categoryName = trim($row[1]);

            $category = WorkerCategory::firstOrCreate(
                ['category' => $categoryName],
                ['created_at' => now(), 'updated_at' => now()]
            );
            $count = Worker::count() + 1;
            do {
                $kode = 'TKG' . str_pad($count, 3, '0', STR_PAD_LEFT);
                $count++;
            } while (Worker::where('code', $kode)->exists());

            // 🔹 Simpan data tukang
            Worker::create([
                'worker_category_id' => $category->id,
                'code'         => $kode,
                'name'         => $row[0],
                'daily_salary' => (int) str_replace(['Rp', '.', ','], '', $row[2]),
                'phone'        => $row[3] ?? null,
                'birth_date'   => $row[4] ? Carbon::parse($row[4]) : null,
                'is_active'    => true,
            ]);
        });
    }
}
