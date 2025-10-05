<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'current_stock',
        'minimum_stock',
        'last_updated',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }
}
