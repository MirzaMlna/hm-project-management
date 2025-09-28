<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPresence extends Model
{
    use HasFactory;

    protected $table = 'presences';

    protected $fillable = [
        'worker_id',
        'date',
        'worker_presence_schedule_id',
        'first_check_in',
        'second_check_in',
        'check_out',
        'is_come_earlier',
        'is_work_longer',
        'is_overtime',
    ];

    public function schedule()
    {
        return $this->belongsTo(WorkerPresenceSchedule::class, 'worker_presence_schedule_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
}
