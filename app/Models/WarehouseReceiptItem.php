<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model WarehouseReceiptItem
 *
 * Định nghĩa thực thể đại diện cho Chi tiết Phiếu Nhập Kho trong hệ thống.
 * Liên kết mỗi sản phẩm nhập kho (product_id) với số lượng, giá nhập, thành tiền,
 * số lô sản xuất (batch_code), ngày hết hạn (expires_at) trong một phiếu nhập kho cụ thể (warehouse_receipt_id).
 */
class WarehouseReceiptItem extends Model
{
    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'warehouse_receipt_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'batch_code',
        'expires_at',
    ];

    /**
     * Mối quan hệ: Một chi tiết phiếu nhập thuộc về một Phiếu nhập kho (WarehouseReceipt).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receipt(): BelongsTo
    {
        return $this->belongsTo(WarehouseReceipt::class, 'warehouse_receipt_id');
    }

    /**
     * Mối quan hệ: Một chi tiết phiếu nhập liên quan đến một Sản phẩm.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
