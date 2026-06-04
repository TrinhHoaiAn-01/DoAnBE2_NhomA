<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng product_reviews.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Đánh giá sản phẩm (product_reviews)
        Schema::create('product_reviews', function (Blueprint $table): void {
            $table->id(); // Khóa chính tự sinh
            $table->foreignId('product_id')->constrained()->cascadeOnDelete(); // Khóa ngoại liên kết sản phẩm (xóa sản phẩm thì xóa các đánh giá liên quan)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Khóa ngoại liên kết tài khoản đánh giá (nếu xóa tài khoản, giữ nguyên đánh giá và đặt null)
            $table->string('customer_name'); // Tên người gửi đánh giá (lưu tên hiển thị tự do hoặc tên tài khoản)
            $table->unsignedTinyInteger('rating'); // Điểm đánh giá (ví dụ từ 1 đến 5 sao)
            $table->string('title')->nullable(); // Tiêu đề ngắn của bài đánh giá
            $table->text('content'); // Nội dung nhận xét chi tiết
            $table->boolean('is_approved')->default(false); // Trạng thái kiểm duyệt hiển thị (true = được duyệt hiển thị công khai, false = chờ duyệt)
            $table->timestamps(); // Thời điểm tạo và cập nhật đánh giá
        });
    }

    /**
     * Hủy bỏ bảng product_reviews khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
