<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerBonus extends Model
{
    protected $fillable = [
        'work_earlier',
        'work_longer',
        'overtime',
    ];
}
