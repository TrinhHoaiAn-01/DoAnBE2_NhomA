<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCheckItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_check_id',
        'product_id',
        'old_stock',
        'actual_stock',
        'difference',
        'note',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
