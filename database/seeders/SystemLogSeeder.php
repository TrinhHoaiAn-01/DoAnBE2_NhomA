<?php

namespace Database\Seeders;

use App\Models\SystemLog;
use Illuminate\Database\Seeder;

class SystemLogSeeder extends Seeder
{
    public function run(): void
    {
        $logs = [
            [
                'user_name' => 'Admin User',
                'action' => 'Update Permission',
                'target_type' => 'Role',
                'old_data' => [
                    'ROLE_2' => ['module' => 'Product', 'can_delete' => false],
                ],
                'new_data' => [
                    'ROLE_2' => ['module' => 'Product', 'can_delete' => true],
                ],
            ],
            [
                'user_name' => 'Product Manager',
                'action' => 'Create Product',
                'target_type' => 'Product',
                'old_data' => [],
                'new_data' => [
                    'sku' => 'NM-SUA-001',
                    'name' => 'Sua tuoi Neo 1L',
                    'stock' => 42,
                ],
            ],
            [
                'user_name' => 'Order Staff',
                'action' => 'Update Order Status',
                'target_type' => 'Order',
                'old_data' => [
                    'code' => 'ORD-20260508-002',
                    'status' => 'pending',
                ],
                'new_data' => [
                    'code' => 'ORD-20260508-002',
                    'status' => 'processing',
                ],
            ],
            [
                'user_name' => 'Warehouse Staff',
                'action' => 'Approve Stock Check',
                'target_type' => 'Warehouse',
                'old_data' => [
                    'sku' => 'NM-CHEN-003',
                    'stock' => 5,
                ],
                'new_data' => [
                    'sku' => 'NM-CHEN-003',
                    'stock' => 7,
                ],
            ],
            [
                'user_name' => 'Admin User',
                'action' => 'Create Supplier',
                'target_type' => 'Supplier',
                'old_data' => [],
                'new_data' => [
                    'name' => 'NeoFresh Foods',
                    'phone' => '02838100001',
                ],
            ],
        ];

        foreach ($logs as $log) {
            SystemLog::query()->updateOrCreate(
                [
                    'user_name' => $log['user_name'],
                    'action' => $log['action'],
                    'target_type' => $log['target_type'],
                ],
                $log
            );
        }
    }
}
