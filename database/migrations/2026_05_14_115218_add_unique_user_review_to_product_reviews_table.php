<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi thêm ràng buộc duy nhất giữa sản phẩm và người dùng để mỗi tài khoản chỉ đánh giá một sản phẩm một lần.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('product_reviews', function (Blueprint $table): void {
            // Thêm ràng buộc duy nhất (Unique) cho cặp cột [product_id, user_id]
            $table->unique(['product_id', 'user_id']);
        });
    }

    /**
     * Thu hồi ràng buộc duy nhất khỏi bảng product_reviews khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table): void {
            // Xóa ràng buộc duy nhất dựa trên cặp cột đã chỉ định
            $table->dropUnique(['product_id', 'user_id']);
        });
    }
};
