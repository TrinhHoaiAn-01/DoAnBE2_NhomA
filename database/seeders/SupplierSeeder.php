<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

/**
 * Lớp SupplierSeeder
 *
 * Khởi tạo dữ liệu mẫu cho danh sách Nhà cung cấp (Suppliers) hàng hóa của hệ thống.
 */
class SupplierSeeder extends Seeder
{
    /**
     * Thực thi chèn dữ liệu mẫu nhà cung cấp.
     *
     * @return void
     */
    public function run(): void
    {
        // Danh sách các nhà cung cấp mẫu đầu vào
        $suppliers = [
            [
                'name' => 'NeoFresh Foods',
                'phone' => '02838100001',
                'address' => '12 Nguyen Van Linh, District 7, Ho Chi Minh City',
            ],
            [
                'name' => 'An Lanh Rice Co.',
                'phone' => '02838100002',
                'address' => '45 Le Van Viet, Thu Duc City, Ho Chi Minh City',
            ],
            [
                'name' => 'CleanMax Household',
                'phone' => '02838100003',
                'address' => '88 Cong Hoa, Tan Binh District, Ho Chi Minh City',
            ],
            [
                'name' => 'LeafCare Beauty',
                'phone' => '02838100004',
                'address' => '21 Hai Ba Trung, District 1, Ho Chi Minh City',
            ],
            [
                'name' => 'Morning Drinks',
                'phone' => '02838100005',
                'address' => '66 Phan Van Tri, Go Vap District, Ho Chi Minh City',
            ],
        ];

        // Lặp qua mảng và thêm hoặc cập nhật thông tin nhà cung cấp
        foreach ($suppliers as $supplier) {
            Supplier::query()->updateOrCreate(
                ['name' => $supplier['name']],
                $supplier
            );
        }
    }
}
