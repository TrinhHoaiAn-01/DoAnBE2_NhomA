<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model WarehouseReceipt
 *
 * Định nghĩa thực thể đại diện cho Phiếu Nhập Kho trong hệ thống.
 * Quản lý thông tin phiếu nhập kho bao gồm mã phiếu, nhà cung cấp (supplier_id),
 * người thực hiện (user_id), tổng tiền thanh toán, ghi chú bổ sung, trạng thái và các mối quan hệ liên quan.
 */
class WarehouseReceipt extends Model
{
    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'supplier_id',
        'user_id',
        'total_amount',
        'note',
        'status',
    ];

    /**
     * Mối quan hệ: Một phiếu nhập kho thuộc về một Nhà cung cấp.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Mối quan hệ: Một phiếu nhập kho được tạo bởi một Người dùng (Nhân viên lập phiếu).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ: Một phiếu nhập kho chứa nhiều Chi tiết phiếu nhập kho (WarehouseReceiptItem).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(WarehouseReceiptItem::class);
    }
}
