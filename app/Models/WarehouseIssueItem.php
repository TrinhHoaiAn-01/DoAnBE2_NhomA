<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model WarehouseIssueItem
 *
 * Định nghĩa thực thể đại diện cho Chi tiết Phiếu Xuất Kho trong hệ thống.
 * Liên kết mỗi sản phẩm cần xuất (product_id) với số lượng xuất tương ứng (quantity)
 * trong một phiếu xuất kho cụ thể (warehouse_issue_id).
 */
class WarehouseIssueItem extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'warehouse_issue_id',
        'product_id',
        'quantity',
    ];

    /**
     * Mối quan hệ: Một chi tiết phiếu xuất kho thuộc về một Sản phẩm.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
