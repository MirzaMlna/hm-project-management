<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'supplier_id',
        'quantity',
        'unit_price',
        'total_price',
        'purchase_date',
        'recipt_photo',
        'item_in_photo',
        'note',
    ];


    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier()
    {
        return $this->belongsTo(ItemSupplier::class, 'supplier_id');
    }
}
