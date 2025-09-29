<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WorkerPresenceExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $headings;
    protected $array;

    public function __construct(array $headings, array $array)
    {
        $this->headings = $headings;
        $this->array = $array;
    }

    public function array(): array
    {
        return $this->array;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
