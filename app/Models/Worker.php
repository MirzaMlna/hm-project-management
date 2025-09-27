<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'birth_date',
        'address',
        'qr_code_path',
        'photo',
        'daily_salary',
        'is_active',
        'note',
    ];
}
