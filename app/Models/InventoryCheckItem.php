<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model InventoryCheckItem
 *
 * Định nghĩa thực thể đại diện cho các sản phẩm bị chênh lệch lệch tồn kho chi tiết trong phiếu kiểm kê.
 * Ghi chép số lượng tồn hệ thống trước kiểm, số lượng thực tế kiểm đếm, chênh lệch lệch (thừa/thiếu) và lý do.
 */
class InventoryCheckItem extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'inventory_check_id',
        'product_id',
        'old_stock',
        'actual_stock',
        'difference',
        'note',
    ];

    /**
     * Mối quan hệ nhiều-một (Many-to-One): Một chi tiết kiểm kê tương ứng với một sản phẩm cụ thể.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

