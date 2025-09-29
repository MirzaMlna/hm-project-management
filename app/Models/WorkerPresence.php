<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPresence extends Model
{
    use HasFactory;

    // Tambahkan ini supaya Laravel pakai tabel yang benar
    protected $table = 'worker_presences';

    protected $fillable = [
        'worker_id',
        'date',
        'first_check_in',
        'is_work_earlier',
        'second_check_in',
        'check_out',
        'is_work_longer',
        'is_overtime',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
