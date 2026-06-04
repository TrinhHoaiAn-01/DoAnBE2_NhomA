<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model Category
 *
 * Định nghĩa thực thể đại diện cho Danh mục sản phẩm trong hệ thống cửa hàng.
 * Quản lý thông tin tên danh mục, đường dẫn tĩnh slug, mô tả, biểu tượng icon, 
 * thứ tự hiển thị và trạng thái hoạt động.
 */
class Category extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    /**
     * Các thuộc tính cần được chuyển đổi kiểu dữ liệu (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot hook của Model.
     * Tự động sinh đường dẫn slug từ tên danh mục khi tiến hành lưu dữ liệu nếu slug bị trống.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::saving(function (self $category): void {
            if (blank($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Mối quan hệ một-nhiều (One-to-Many): Một danh mục có thể chứa nhiều sản phẩm.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

