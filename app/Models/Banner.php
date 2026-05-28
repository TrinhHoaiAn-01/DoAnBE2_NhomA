<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Banner
 *
 * Định nghĩa thực thể đại diện cho các Banner quảng cáo của hệ thống.
 * Quản lý thông tin hình ảnh, tiêu đề, liên kết, phân vùng hiển thị, thứ tự sắp xếp và thời gian kích hoạt.
 */
class Banner extends Model
{
    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'image_url',
        'link',
        'position',
        'sort_order',
        'is_active',
        'start_date',
        'end_date',
    ];
    
    /**
     * Các thuộc tính cần được chuyển đổi kiểu dữ liệu (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];
}

