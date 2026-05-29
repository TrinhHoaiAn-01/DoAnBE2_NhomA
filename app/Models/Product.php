<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model Product
 *
 * Định nghĩa thực thể đại diện cho các Sản phẩm của cửa hàng NeoMart.
 * Quản lý danh mục cha, tên, đường dẫn tĩnh slug, mã SKU định danh, thương hiệu, 
 * mô tả chi tiết, giá bán, giá gốc (trước giảm giá), số lượng tồn kho thực tế, ảnh sản phẩm và trạng thái ẩn hiện.
 */
class Product extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'brand',
        'description',
        'price',
        'original_price',
        'stock',
        'image_url',
        'is_active',
    ];

    /**
     * Các thuộc tính cần được chuyển đổi kiểu dữ liệu (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Boot hook của Model.
     * Tự động sinh đường dẫn tĩnh slug từ tên sản phẩm khi tiến hành lưu dữ liệu nếu slug bị trống.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::saving(function (self $product): void {
            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Mối quan hệ nhiều-một (Many-to-One): Một sản phẩm thuộc về một danh mục (Category) nhất định.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Mối quan hệ một-nhiều (One-to-Many): Một sản phẩm có thể nhận được nhiều đánh giá (ProductReview) từ người dùng.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }
    
    /**
     * Mối quan hệ một-nhiều (One-to-Many): Lịch sử thẻ kho, ghi chép biến động xuất nhập tồn của sản phẩm.
     * Sắp xếp theo ngày tạo giảm dần (biến động mới nhất đưa lên đầu).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class)->orderBy('created_at', 'desc');
    }
}

