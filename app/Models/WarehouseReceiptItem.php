<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseReceiptItem extends Model
{
    protected $fillable = [
        'warehouse_receipt_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'batch_code',
        'expires_at',
    ];

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(WarehouseReceipt::class, 'warehouse_receipt_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
