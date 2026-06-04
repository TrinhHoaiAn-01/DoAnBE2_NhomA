<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi thêm các cột liên quan đến chương trình khuyến mãi vào bảng orders.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            // Khóa ngoại liên kết với bảng promotions (nếu chương trình khuyến mãi bị xóa, giữ nguyên lịch sử đơn và đặt null)
            $table->foreignId('promotion_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            // Lưu mã khuyến mãi tại thời điểm đặt hàng (ví dụ: GIAM20) để lưu lịch sử
            $table->string('promotion_code')->nullable()->after('note');
            // Số tiền được giảm giá thực tế của đơn hàng
            $table->decimal('discount_total', 12, 2)->default(0)->after('shipping_fee');
        });
    }

    /**
     * Thu hồi các cột khuyến mãi khỏi bảng orders khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('promotion_id'); // Hủy ràng buộc khóa ngoại và cột promotion_id
            $table->dropColumn(['promotion_code', 'discount_total']); // Hủy các cột mã giảm và tổng giảm
        });
    }
};
