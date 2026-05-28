<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi thêm các cột lịch giao hàng (ngày giao và khung giờ giao) vào bảng orders.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            // Ngày hẹn giao hàng (delivery_date) sau cột dịch vụ vận chuyển
            $table->date('delivery_date')->nullable()->after('shipping_service');
            // Khung giờ hẹn giao hàng (delivery_time_slot, ví dụ: 08:00 - 12:00) sau cột ngày giao hàng
            $table->string('delivery_time_slot')->nullable()->after('delivery_date');
        });
    }

    /**
     * Thu hồi các cột lịch giao hàng khỏi bảng orders khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn(['delivery_date', 'delivery_time_slot']);
        });
    }
};
