<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model StockHistory
 *
 * Định nghĩa thực thể đại diện cho Lịch sử biến động kho hàng (Thẻ kho) của sản phẩm.
 * Ghi nhận mỗi lần tăng/giảm tồn kho: loại biến động (nhập/xuất), số lượng thay đổi, 
 * loại chứng từ tham chiếu (phí nhập, xuất kho, kiểm kê), mã chứng từ và ghi chú lý do chi tiết.
 */
class StockHistory extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'reference_type',
        'reference_code',
        'note',
    ];

    /**
     * Mối quan hệ nhiều-một (Many-to-One): Một dòng lịch sử kho (Thẻ kho) thuộc về một sản phẩm (Product) cụ thể.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

