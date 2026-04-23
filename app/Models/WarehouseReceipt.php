<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseReceipt extends Model
{
    protected $fillable = [
        'receipt_code',
        'supplier_id',
        'created_by',
        'total_amount',
        'note',
        'status',
    ];

    // Quan hệ với bảng nhà cung cấp
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    // Quan hệ với bảng chi tiết phiếu nhập
    public function details(): HasMany
    {
        return $this->hasMany(WarehouseReceiptDetail::class, 'receipt_id');
    }
}
