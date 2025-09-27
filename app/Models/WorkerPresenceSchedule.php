<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPresenceSchedule extends Model
{
    use HasFactory;

    protected $table = 'worker_presence_schedules';

    protected $fillable = [
        'first_check_in_start',
        'first_check_in_end',
        'second_check_in_start',
        'second_check_in_end',
        'check_out_start',
        'check_out_end',
    ];
}
