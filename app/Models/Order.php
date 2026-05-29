<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Order
 *
 * Định nghĩa thực thể đại diện cho các Đơn đặt hàng của hệ thống.
 * Quản lý thông tin mã đơn hàng, khách hàng, địa chỉ giao hàng, lịch trình giao, 
 * thông tin khuyến mãi áp dụng, phương thức & trạng thái thanh toán, tổng quan chi phí (tạm tính, ship, giảm giá, tổng tiền).
 */
class Order extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'promotion_id',
        'code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_district',
        'shipping_service',
        'delivery_date',
        'delivery_time_slot',
        'note',
        'promotion_code',
        'payment_method',
        'payment_status',
        'status',
        'subtotal',
        'shipping_fee',
        'discount_total',
        'total',
    ];

    /**
     * Các thuộc tính cần được chuyển đổi kiểu dữ liệu (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Mối quan hệ nhiều-một (Many-to-One): Một đơn hàng có thể thuộc về một người dùng đã đăng nhập (User).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ một-nhiều (One-to-Many): Một đơn hàng có chứa nhiều chi tiết các mặt hàng sản phẩm (OrderItem).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

