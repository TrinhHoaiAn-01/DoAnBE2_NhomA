<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model RolePermission
 *
 * Định nghĩa thực thể đại diện cho Bảng Phân quyền (Permissions) theo vai trò của người dùng.
 * Quản lý thông tin mã vai trò, tên vai trò, phân hệ/module kiểm soát và các quyền thao tác cơ bản:
 * Quyền xem (can_view), thêm (can_add), sửa (can_edit), xóa (can_delete) và phê duyệt (can_approve).
 */
class RolePermission extends Model
{
    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_code',
        'role_name',
        'module',
        'can_view',
        'can_add',
        'can_edit',
        'can_delete',
        'can_approve',
    ];

    /**
     * Các thuộc tính cần được chuyển đổi kiểu dữ liệu (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'can_view' => 'boolean',
        'can_add' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
        'can_approve' => 'boolean',
    ];
}

