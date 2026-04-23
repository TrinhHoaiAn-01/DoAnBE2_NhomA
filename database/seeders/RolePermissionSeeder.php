<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['code' => 'ROLE_1', 'name' => 'Người 1 (Khách hàng)', 'module' => 'Customer'],
            ['code' => 'ROLE_2', 'name' => 'Người 2 (Sản phẩm)', 'module' => 'Product'],
            ['code' => 'ROLE_3', 'name' => 'Người 3 (Đơn hàng)', 'module' => 'Order'],
            ['code' => 'ROLE_4', 'name' => 'Người 4 (Kho hàng)', 'module' => 'Warehouse'],
            ['code' => 'ROLE_5', 'name' => 'Người 5 (Hệ thống)', 'module' => 'System'],
        ];

        foreach ($roles as $role) {
            RolePermission::create([
                'role_code' => $role['code'],
                'role_name' => $role['name'],
                'module' => $role['module'],
                'can_view' => true,
                'can_add' => true,
                'can_edit' => true,
                'can_delete' => ($role['code'] === 'ROLE_5'), // Chỉ Người 5 có quyền xóa mặc định
                'can_approve' => ($role['code'] === 'ROLE_5' || $role['code'] === 'ROLE_4'),
            ]);
        }
    }
}
