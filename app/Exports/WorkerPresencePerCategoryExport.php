<?php

namespace App\Exports;

use App\Models\WorkerBonus;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\WorkerCategory;
use Carbon\Carbon;

class WorkerPresencePerCategoryExport implements WithMultipleSheets
{
    protected $period;
    protected $dateRange;
    protected $categories;
    protected $presences;

    public function __construct(array $period, string $dateRange, $categories, $presences)
    {
        $this->period = $period;
        $this->dateRange = $dateRange;
        $this->categories = $categories;
        $this->presences = $presences;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Ambil bonus config (anggap hanya 1 baris di tabel worker_bonuses)
        $bonusConfig = WorkerBonus::first();

        foreach ($this->categories as $category) {
            $workers = $category->workers->sortBy('name');

            $rowsPresensi = [];
            $rowsGaji = [];
            $no = 1;

            foreach ($workers as $worker) {
                $row = [];
                $row[] = $no;
                $row[] = $worker->code;
                $row[] = $worker->name;
                $row[] = $worker->daily_salary ?? '-';

                $workerPresences = $this->presences->get($worker->id, collect());

                $totalPoints = 0;
                $dla = $kll = $lm = 0;

                foreach ($this->period as $date) {
                    $p = $workerPresences->firstWhere('date', $date);

                    if ($p) {
                        $count = 0;
                        if ($p->first_check_in) $count++;
                        if ($p->second_check_in) $count++;
                        if ($p->check_out) $count++;

                        if ($count >= 3) $points = 1;
                        elseif ($count == 2) $points = 0.5;
                        else $points = 0;

                        if ($p->is_work_earlier) $dla++;
                        if ($p->is_work_longer) $kll++;
                        if ($p->is_overtime) $lm++;
                    } else {
                        $points = 0;
                    }

                    $row[] = (float)$points;
                    $totalPoints += (float)$points;
                }

                $row[] = (float)$totalPoints;
                $row[] = (int)$dla;
                $row[] = (int)$kll;
                $row[] = (int)$lm;
                $row[] = $no;

                $rowsPresensi[] = $row;

                // === Perhitungan gaji ===
                $upah = $totalPoints * $worker->daily_salary;
                $bonusDla = $dla * ($bonusConfig->work_earlier ?? 0);
                $bonusKll = $kll * ($bonusConfig->work_longer ?? 0);
                $bonusLm  = $lm * $worker->daily_salary;
                $totalGaji = $upah + $bonusDla + $bonusKll + $bonusLm;

                $rowsGaji[] = [
                    $no,
                    $worker->code,
                    $worker->name,
                    $upah,
                    $bonusDla,
                    $bonusKll,
                    $bonusLm,
                    $totalGaji,
                    '', // TTD
                    ''  // Keterangan
                ];

                $no++;
            }

            // Tambah sheet presensi
            $sheets[] = new WorkerPresenceExport(
                $rowsPresensi,
                array_map(fn($d) => Carbon::parse($d)->format('d-M'), $this->period),
                $this->dateRange,
                'TUKANG ' . $category->category
            );

            // Tambah sheet gaji
            $sheets[] = new WorkerSalaryExport(
                $rowsGaji,
                $category->category
            );
        }

        return $sheets;
    }
}
