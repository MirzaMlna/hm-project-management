<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemSupplier extends Model
{
    protected $fillable = [
        'code',
        'supplier',
        'phone',
        'address',
        'description',
    ];
}
