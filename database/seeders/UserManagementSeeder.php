<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserManagementSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'phone' => '0901000001',
                'role_id' => 5,
                'status' => true,
                'avatar_url' => 'https://placehold.co/160x160?text=Admin',
            ],
            [
                'name' => 'Product Manager',
                'username' => 'product_manager',
                'email' => 'product@example.com',
                'phone' => '0901000002',
                'role_id' => 2,
                'status' => true,
                'avatar_url' => 'https://placehold.co/160x160?text=Product',
            ],
            [
                'name' => 'Order Staff',
                'username' => 'order_staff',
                'email' => 'order@example.com',
                'phone' => '0901000003',
                'role_id' => 3,
                'status' => true,
                'avatar_url' => 'https://placehold.co/160x160?text=Order',
            ],
            [
                'name' => 'Warehouse Staff',
                'username' => 'warehouse_staff',
                'email' => 'warehouse@example.com',
                'phone' => '0901000004',
                'role_id' => 4,
                'status' => true,
                'avatar_url' => 'https://placehold.co/160x160?text=Warehouse',
            ],
            [
                'name' => 'Customer Demo',
                'username' => 'customer_demo',
                'email' => 'customer@example.com',
                'phone' => '0901000005',
                'role_id' => 1,
                'status' => true,
                'avatar_url' => 'https://placehold.co/160x160?text=Customer',
            ],
            [
                'name' => 'Locked Account',
                'username' => 'locked_account',
                'email' => 'locked@example.com',
                'phone' => '0901000006',
                'role_id' => 1,
                'status' => false,
                'avatar_url' => 'https://placehold.co/160x160?text=Locked',
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                $user + ['password' => Hash::make('password')]
            );
        }
    }
}
