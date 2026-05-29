<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng faqs.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Câu hỏi thường gặp (faqs)
        Schema::create('faqs', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->string('category'); // Phân loại FAQ (ví dụ: Thanh toán, Giao hàng...)
            $table->string('question'); // Câu hỏi thắc mắc thường gặp
            $table->text('answer'); // Câu trả lời chi tiết tương ứng
            $table->integer('sort_order')->default(0); // Thứ tự sắp xếp hiển thị
            $table->boolean('is_active')->default(true); // Trạng thái hiển thị công khai (true = hiển thị, false = ẩn)
            $table->timestamps(); // Thời điểm tạo và cập nhật FAQ
        });
    }

    /**
     * Hủy bỏ bảng faqs khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
