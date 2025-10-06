<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOut extends Model
{
    protected $fillable = [
        'item_id',
        'development_point_id',
        'quantity',
        'date_out',
        'note',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function developmentPoint()
    {
        return $this->belongsTo(DevelopmentPoint::class);
    }
}
