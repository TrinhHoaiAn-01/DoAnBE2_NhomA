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
                'email' => 'admin@example.com',
                'phone' => '0901000001',
                'role_id' => 5,
                'status' => 'active',
                'avatar_url' => 'https://placehold.co/160x160?text=Admin',
            ],
            [
                'name' => 'Product Manager',
                'email' => 'product@example.com',
                'phone' => '0901000002',
                'role_id' => 2,
                'status' => 'active',
                'avatar_url' => 'https://placehold.co/160x160?text=Product',
            ],
            [
                'name' => 'Order Staff',
                'email' => 'order@example.com',
                'phone' => '0901000003',
                'role_id' => 3,
                'status' => 'active',
                'avatar_url' => 'https://placehold.co/160x160?text=Order',
            ],
            [
                'name' => 'Warehouse Staff',
                'email' => 'warehouse@example.com',
                'phone' => '0901000004',
                'role_id' => 4,
                'status' => 'active',
                'avatar_url' => 'https://placehold.co/160x160?text=Warehouse',
            ],
            [
                'name' => 'Customer Demo',
                'email' => 'customer@example.com',
                'phone' => '0901000005',
                'role_id' => 1,
                'status' => 'active',
                'avatar_url' => 'https://placehold.co/160x160?text=Customer',
            ],
            [
                'name' => 'Locked Account',
                'email' => 'locked@example.com',
                'phone' => '0901000006',
                'role_id' => 1,
                'status' => 'inactive',
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
