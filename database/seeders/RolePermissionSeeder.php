<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use Illuminate\Database\Seeder;

/**
 * Lớp RolePermissionSeeder
 *
 * Khởi tạo dữ liệu cấu hình Vai trò và Quyền hạn mặc định của hệ thống.
 * Phân quyền thao tác trên các module chức năng (Khách hàng, Sản phẩm, Đơn hàng, Kho, Hệ thống).
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Thực thi chèn dữ liệu cấu hình vai trò phân quyền.
     *
     * @return void
     */
    public function run(): void
    {
        // Danh sách các vai trò mẫu gắn với từng phân hệ/module kiểm soát
        $roles = [
            ['code' => 'ROLE_1', 'name' => 'Người 1 (Khách hàng)', 'module' => 'Customer'],
            ['code' => 'ROLE_2', 'name' => 'Người 2 (Sản phẩm)', 'module' => 'Product'],
            ['code' => 'ROLE_3', 'name' => 'Người 3 (Đơn hàng)', 'module' => 'Order'],
            ['code' => 'ROLE_4', 'name' => 'Người 4 (Kho hàng)', 'module' => 'Warehouse'],
            ['code' => 'ROLE_5', 'name' => 'Người 5 (Hệ thống)', 'module' => 'System'],
        ];

        // Lặp qua mảng để tạo mới hoặc cập nhật cấu hình quyền hạn cơ bản
        foreach ($roles as $role) {

            RolePermission::query()->updateOrCreate(
                [
                    'role_code' => $role['code'],
                    'module' => $role['module'],
                ],
                [
                    'role_name' => $role['name'],
                    'can_view' => true,
                    'can_add' => true,
                    'can_edit' => true,
                    // Chỉ quản trị viên hệ thống (ROLE_5) mới có quyền xóa
                    'can_delete' => $role['code'] === 'ROLE_5',
                    // Quản trị viên hệ thống (ROLE_5) và nhân viên kho (ROLE_4) có quyền phê duyệt
                    'can_approve' => $role['code'] === 'ROLE_5' || $role['code'] === 'ROLE_4',
                ]
            );
        }
    }
}