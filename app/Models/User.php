<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User
 *
 * Định nghĩa thực thể đại diện cho Người dùng (Tài khoản) trong hệ thống.
 * Quản lý thông tin cá nhân, tài khoản đăng nhập, mật khẩu, vai trò phân quyền (role_id)
 * và trạng thái hoạt động của người dùng.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'name',

        'username',

        'email',

        'phone',

        'avatar_url',

        'home_address',

        'gender',

        'date_of_birth',

        'password',

        'role_id',

        'status',
    ];

    /**
     * Các thuộc tính cần được ẩn khi chuyển đổi sang dạng mảng hoặc JSON.
     * Thường dùng để bảo mật thông tin nhạy cảm như mật khẩu.
     *
     * @var array<int, string>
     */
    protected $hidden = [

        'password',

        'remember_token',
    ];

    /**
     * Cấu hình chuyển đổi kiểu dữ liệu cho các thuộc tính (Casting).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',

            'password' => 'hashed',

            'status' => 'boolean',

            'date_of_birth' => 'date',
        ];
    }
}