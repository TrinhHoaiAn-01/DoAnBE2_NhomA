<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseReceiptDetail extends Model
{
    protected $fillable = [
        'receipt_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'batch_number',
        'expiry_date',
    ];

    // Quan hệ ngược về Phiếu nhập
    public function receipt(): BelongsTo
    {
        return $this->belongsTo(WarehouseReceipt::class, 'receipt_id');
    }
}
