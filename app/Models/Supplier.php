<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Supplier
 *
 * Định nghĩa thực thể đại diện cho các Nhà cung cấp (Supplier) sản phẩm hàng hóa cho hệ thống siêu thị.
 * Quản lý thông tin cơ bản: Tên nhà cung cấp, số điện thoại liên hệ và địa chỉ trụ sở.
 */
class Supplier extends Model
{
    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'phone', 'address'];
}

