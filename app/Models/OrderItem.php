<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model OrderItem
 *
 * Định nghĩa thực thể đại diện cho chi tiết của từng mặt hàng sản phẩm nằm trong một đơn đặt hàng.
 * Quản lý thông tin liên kết đơn hàng, sản phẩm gốc, bản sao tên sản phẩm & mã SKU tại thời điểm mua (tránh mất lịch sử khi sản phẩm đổi tên), 
 * đơn giá, số lượng mua và thành tiền tạm tính.
 */
class OrderItem extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'sku',
        'price',
        'quantity',
        'subtotal',
    ];

    /**
     * Các thuộc tính cần được chuyển đổi kiểu dữ liệu (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Mối quan hệ nhiều-một (Many-to-One): Một chi tiết đơn hàng phải thuộc về một đơn hàng chính (Order).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mối quan hệ nhiều-một (Many-to-One): Một chi tiết đơn hàng tham chiếu tới một sản phẩm (Product) cụ thể.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

