<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Lớp DatabaseSeeder
 *
 * Seeder trung tâm chịu trách nhiệm kích hoạt toàn bộ các Seeder thành phần
 * để thiết lập dữ liệu ban đầu cho cơ sở dữ liệu.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Thực thi tiến trình chèn dữ liệu mẫu vào cơ sở dữ liệu.
     *
     * @return void
     */
    public function run(): void
    {
        // Gọi các seeder theo thứ tự hợp lý để tránh lỗi khóa ngoại
        $this->call([
            RolePermissionSeeder::class,
            UserManagementSeeder::class,
            CatalogSeeder::class,
            SupplierSeeder::class,
            OrderSeeder::class,
            SystemLogSeeder::class,
        ]);
    }
}
