<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Lớp OrderSeeder
 *
 * Khởi tạo dữ liệu mẫu cho Đơn hàng (Orders) và Chi tiết đơn hàng (Order Items).
 * Mô phỏng các trạng thái giao dịch (chờ xử lý, đang giao, đã giao, đã thanh toán...).
 */
class OrderSeeder extends Seeder
{
    /**
     * Thực thi chèn dữ liệu mẫu cho các đơn hàng.
     *
     * @return void
     */
    public function run(): void
    {
        // Gọi Seeder tài khoản và sản phẩm làm dữ liệu nền
        $this->call([
            UserManagementSeeder::class,
            CatalogSeeder::class,
        ]);

        // Danh sách các đơn hàng giả lập với nhiều trạng thái khác nhau
        $orders = [
            [
                'code' => 'ORD-20260508-001',
                'user_email' => 'customer@example.com',
                'customer_name' => 'Customer Demo',
                'customer_email' => 'customer@example.com',
                'customer_phone' => '0901000005',
                'shipping_address' => '101 Nguyen Trai, District 5, Ho Chi Minh City',
                'note' => 'Giao hang trong gio hanh chinh.',
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'status' => 'pending',
                'shipping_fee' => 20000,
                'items' => [
                    ['sku' => 'NM-SUA-001', 'quantity' => 2],
                    ['sku' => 'NM-BANH-006', 'quantity' => 1],
                ],
            ],
            [
                'code' => 'ORD-20260508-002',
                'user_email' => 'customer@example.com',
                'customer_name' => 'Nguyen Van An',
                'customer_email' => 'an.nguyen@example.com',
                'customer_phone' => '0912222333',
                'shipping_address' => '25 Cach Mang Thang 8, District 3, Ho Chi Minh City',
                'note' => null,
                'payment_method' => 'bank_transfer',
                'payment_status' => 'paid',
                'status' => 'processing',
                'shipping_fee' => 15000,
                'items' => [
                    ['sku' => 'NM-GAO-005', 'quantity' => 1],
                    ['sku' => 'NM-CHEN-003', 'quantity' => 2],
                ],
            ],
            [
                'code' => 'ORD-20260508-003',
                'user_email' => null,
                'customer_name' => 'Tran Thi Binh',
                'customer_email' => 'binh.tran@example.com',
                'customer_phone' => '0988888777',
                'shipping_address' => '9 Pham Van Dong, Thu Duc City, Ho Chi Minh City',
                'note' => 'Khach mua khong can tai khoan.',
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'status' => 'shipping',
                'shipping_fee' => 25000,
                'items' => [
                    ['sku' => 'NM-CAPHE-004', 'quantity' => 3],
                    ['sku' => 'NM-KHAN-007', 'quantity' => 2],
                ],
            ],
            [
                'code' => 'ORD-20260508-004',
                'user_email' => 'customer@example.com',
                'customer_name' => 'Le Minh Chau',
                'customer_email' => 'chau.le@example.com',
                'customer_phone' => '0977111222',
                'shipping_address' => '72 Nguyen Dinh Chieu, District 1, Ho Chi Minh City',
                'note' => null,
                'payment_method' => 'cod',
                'payment_status' => 'paid',
                'status' => 'completed',
                'shipping_fee' => 0,
                'items' => [
                    ['sku' => 'NM-SRM-008', 'quantity' => 1],
                    ['sku' => 'NM-SUA-001', 'quantity' => 1],
                ],
            ],
        ];

        // Lặp qua mảng đơn hàng để chèn dữ liệu và tính tổng tiền tự động
        foreach ($orders as $orderData) {
            $items = $orderData['items'];
            $userEmail = $orderData['user_email'];
            unset($orderData['items'], $orderData['user_email']);

            $user = $userEmail
                ? User::query()->where('email', $userEmail)->first()
                : null;

            $subtotal = 0;
            $preparedItems = [];

            // Chuẩn bị chi tiết dòng sản phẩm và tính thành tiền
            foreach ($items as $item) {
                $product = Product::query()->where('sku', $item['sku'])->first();

                if (! $product) {
                    continue;
                }

                $lineSubtotal = (float) $product->price * $item['quantity'];
                $subtotal += $lineSubtotal;
                $preparedItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $lineSubtotal,
                ];
            }

            if ($preparedItems === []) {
                continue;
            }

            // Tạo/Cập nhật bản ghi đơn hàng
            $order = Order::query()->updateOrCreate(
                ['code' => $orderData['code']],
                $orderData + [
                    'user_id' => $user?->id,
                    'subtotal' => $subtotal,
                    'total' => $subtotal + $orderData['shipping_fee'],
                ]
            );

            // Làm sạch và chèn mới các dòng sản phẩm chi tiết
            $order->items()->delete();

            foreach ($preparedItems as $preparedItem) {
                $order->items()->create($preparedItem);
            }
        }
    }
}
