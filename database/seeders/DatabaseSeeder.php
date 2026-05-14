<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi các seeder theo thứ tự hợp lý để tránh lỗi khóa ngoại (nếu có)
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
