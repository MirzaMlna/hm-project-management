<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPresence extends Model
{
    use HasFactory;

    protected $table = 'worker_presences';

    protected $fillable = [
        'worker_id',
        'date',
        'first_check_in',
        'second_check_in',
        'work_longer_count',
        'is_overtime',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
