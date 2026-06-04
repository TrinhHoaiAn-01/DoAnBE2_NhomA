<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Lớp UserManagementSeeder
 *
 * Khởi tạo danh sách Tài khoản người dùng mẫu tương ứng với các vai trò phân quyền khác nhau.
 * Tất cả các tài khoản mặc định đều có mật khẩu đăng nhập là "password".
 */
class UserManagementSeeder extends Seeder
{
    /**
     * Thực thi chèn dữ liệu mẫu tài khoản người dùng.
     *
     * @return void
     */
    public function run(): void
    {
        // Danh sách tài khoản mẫu của các phân hệ quản trị và khách hàng
        $users = [
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'phone' => '0901000001',
                'role_id' => 5, // Quyền Quản trị viên cao nhất
                'status' => true,
                'avatar_url' => 'https://placehold.co/160x160?text=Admin',
            ],
            [
                'name' => 'Product Manager',
                'username' => 'product_manager',
                'email' => 'product@example.com',
                'phone' => '0901000002',
                'role_id' => 2, // Quyền Nhân viên Quản lý Sản phẩm
                'status' => true,
                'avatar_url' => 'https://placehold.co/160x160?text=Product',
            ],
            [
                'name' => 'Order Staff',
                'username' => 'order_staff',
                'email' => 'order@example.com',
                'phone' => '0901000003',
                'role_id' => 3, // Quyền Nhân viên xử lý Đơn hàng
                'status' => true,
                'avatar_url' => 'https://placehold.co/160x160?text=Order',
            ],
            [
                'name' => 'Warehouse Staff',
                'username' => 'warehouse_staff',
                'email' => 'warehouse@example.com',
                'phone' => '0901000004',
                'role_id' => 4, // Quyền Nhân viên Thủ kho
                'status' => true,
                'avatar_url' => 'https://placehold.co/160x160?text=Warehouse',
            ],
            [
                'name' => 'Customer Demo',
                'username' => 'customer_demo',
                'email' => 'customer@example.com',
                'phone' => '0901000005',
                'role_id' => 1, // Quyền Khách hàng mua sắm
                'status' => true,
                'avatar_url' => 'https://placehold.co/160x160?text=Customer',
            ],
            [
                'name' => 'Locked Account',
                'username' => 'locked_account',
                'email' => 'locked@example.com',
                'phone' => '0901000006',
                'role_id' => 1, // Tài khoản khách hàng đang bị khóa
                'status' => false,
                'avatar_url' => 'https://placehold.co/160x160?text=Locked',
            ],
        ];

        // Lặp qua danh sách tài khoản để chèn và tự động Hash mật khẩu mặc định
        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                $user + ['password' => Hash::make('password')]
            );
        }
    }
}
