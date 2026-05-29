<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model InventoryCheck
 *
 * Định nghĩa thực thể đại diện cho các phiếu kiểm kê kho hàng.
 * Quản lý thông tin mã kiểm kê, nhân viên thực hiện kiểm kê, ghi chú và trạng thái phiếu kiểm kê.
 */
class InventoryCheck extends Model
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
        'note',
        'status',
    ];

    /**
     * Mối quan hệ nhiều-một (Many-to-One): Một phiếu kiểm kê được tạo bởi một nhân viên (User).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ một-nhiều (One-to-Many): Một phiếu kiểm kê có thể chứa nhiều chi tiết kiểm kê sản phẩm lệch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(InventoryCheckItem::class);
    }
}

