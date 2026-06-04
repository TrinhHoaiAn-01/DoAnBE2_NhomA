<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Promotion
 *
 * Định nghĩa thực thể đại diện cho các chương trình khuyến mãi và mã giảm giá (Coupon/Voucher).
 * Quản lý thông tin mã giảm giá, tên, loại giảm giá (percent hoặc fixed), giá trị giảm, 
 * giá trị đơn hàng tối thiểu, giới hạn lượt dùng, số lượt đã dùng, thời gian áp dụng và trạng thái kích hoạt.
 */
class Promotion extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'discount_type',
        'discount_value',
        'minimum_order',
        'usage_limit',
        'used_count',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    /**
     * Các thuộc tính cần được chuyển đổi kiểu dữ liệu (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Tính toán số tiền được giảm giá dựa trên tổng tiền tạm tính của đơn hàng.
     * Hỗ trợ tính giảm theo phần trăm (giới hạn tối đa bằng tổng tiền) và giảm số tiền cố định.
     *
     * @param float $subtotal Tổng tiền tạm tính của đơn hàng
     * @return float Số tiền được giảm giá thực tế
     */
    public function discountFor(float $subtotal): float
    {
        // Giảm giá theo phần trăm (ví dụ: giảm 10%)
        if ($this->discount_type === 'percent') {
            // Không giảm vượt quá 100% giá trị đơn hàng
            return min($subtotal, $subtotal * ((float) $this->discount_value / 100));
        }

        // Giảm giá theo số tiền cố định (ví dụ: giảm 50.000đ)
        // Số tiền giảm không được lớn hơn tổng giá trị đơn hàng
        return min($subtotal, (float) $this->discount_value);
    }
}

