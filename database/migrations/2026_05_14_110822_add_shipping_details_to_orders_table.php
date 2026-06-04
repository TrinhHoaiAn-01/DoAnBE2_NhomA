<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi thêm các cột liên quan đến vận chuyển vào bảng orders.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            // Thêm khu vực vận chuyển (noi_thanh = nội thành, ngoai_thanh = ngoại thành) sau cột địa chỉ giao hàng
            $table->string('shipping_district')->default('noi_thanh')->after('shipping_address');
            // Thêm loại dịch vụ giao hàng (standard = tiêu chuẩn, fast = giao nhanh) sau cột khu vực vận chuyển
            $table->string('shipping_service')->default('standard')->after('shipping_district');
        });
    }

    /**
     * Thu hồi các cột vận chuyển khỏi bảng orders khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn(['shipping_district', 'shipping_service']);
        });
    }
};
