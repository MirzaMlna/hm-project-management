<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'item_category_id',
        'code',
        'name',
        'unit',
        'photo',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function stock()
    {
        return $this->hasOne(ItemStock::class, 'item_id');
    }
}
