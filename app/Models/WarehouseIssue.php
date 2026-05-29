<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model WarehouseIssue
 *
 * Định nghĩa thực thể đại diện cho Phiếu Xuất Kho trong hệ thống.
 * Quản lý thông tin phiếu xuất kho bao gồm mã phiếu, người thực hiện (user_id), lý do xuất,
 * ghi chú bổ sung, trạng thái phiếu và các mối quan hệ liên quan.
 */
class WarehouseIssue extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'user_id',
        'reason',
        'note',
        'status',
    ];

    /**
     * Mối quan hệ: Một phiếu xuất kho thuộc về một Người dùng (Nhân viên lập phiếu).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ: Một phiếu xuất kho chứa nhiều Chi tiết phiếu xuất kho (WarehouseIssueItem).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(WarehouseIssueItem::class);
    }
}
