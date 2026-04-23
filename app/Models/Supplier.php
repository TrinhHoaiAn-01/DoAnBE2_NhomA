<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    // Cấu hình các trường được phép thêm/sửa
    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    // Ép kiểu dữ liệu
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
