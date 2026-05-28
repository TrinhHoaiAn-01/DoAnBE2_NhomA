<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng promotions.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Chương trình khuyến mãi / Mã giảm giá (promotions)
        Schema::create('promotions', function (Blueprint $table): void {
            $table->id(); // Khóa chính tự sinh
            $table->string('code')->unique(); // Mã coupon giảm giá duy nhất (ví dụ: GIAM20, TET2026...)
            $table->string('name'); // Tên chương trình khuyến mãi
            $table->string('discount_type')->default('fixed'); // Loại giảm giá (fixed = số tiền cố định, percent = phần trăm %)
            $table->decimal('discount_value', 12, 2); // Giá trị giảm giá tương ứng với loại giảm giá
            $table->decimal('minimum_order', 12, 2)->default(0); // Giá trị đơn hàng tối thiểu để áp dụng mã giảm giá
            $table->unsignedInteger('usage_limit')->nullable(); // Giới hạn số lần sử dụng tối đa của mã (null = không giới hạn)
            $table->unsignedInteger('used_count')->default(0); // Số lần mã giảm giá đã được sử dụng thực tế
            $table->timestamp('starts_at')->nullable(); // Thời điểm bắt đầu có hiệu lực
            $table->timestamp('ends_at')->nullable(); // Thời điểm kết thúc hiệu lực
            $table->boolean('is_active')->default(true); // Trạng thái kích hoạt (true = có thể dùng, false = tạm khóa)
            $table->timestamps(); // Thời điểm tạo và cập nhật mã giảm giá
        });
    }

    /**
     * Hủy bỏ bảng promotions khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
