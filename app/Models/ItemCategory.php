<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $fillable = ['category'];

    public function items()
    {
        return $this->hasMany(Item::class, 'item_category_id');
    }
}
