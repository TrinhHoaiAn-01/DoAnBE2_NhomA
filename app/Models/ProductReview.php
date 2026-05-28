<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model ProductReview
 *
 * Định nghĩa thực thể đại diện cho các Đánh giá/Phản hồi sản phẩm từ khách hàng.
 * Quản lý thông tin liên kết sản phẩm, tài khoản thành viên (nếu đã đăng nhập), 
 * tên người đánh giá, số sao đánh giá (1-5), tiêu đề, nội dung đánh giá và trạng thái phê duyệt (is_approved).
 */
class ProductReview extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'customer_name',
        'rating',
        'title',
        'content',
        'is_approved',
    ];

    /**
     * Các thuộc tính cần được chuyển đổi kiểu dữ liệu (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Mối quan hệ nhiều-một (Many-to-One): Một đánh giá thuộc về một sản phẩm (Product) nhất định.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mối quan hệ nhiều-một (Many-to-One): Một đánh giá có thể được viết bởi một thành viên (User) đã đăng nhập.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

