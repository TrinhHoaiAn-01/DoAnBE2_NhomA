<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng contacts.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Hỗ trợ khách hàng / Liên hệ (contacts)
        Schema::create('contacts', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->string('name'); // Họ tên khách hàng gửi liên hệ
            $table->string('email'); // Email liên hệ của khách hàng
            $table->string('phone')->nullable(); // Số điện thoại khách hàng
            $table->string('subject'); // Chủ đề / Tiêu đề liên hệ
            $table->text('message'); // Nội dung phản hồi / thắc mắc chi tiết
            $table->enum('status', ['pending', 'resolved'])->default('pending'); // Trạng thái xử lý (pending = chưa xử lý, resolved = đã phản hồi/giải quyết)
            $table->text('reply_message')->nullable(); // Nội dung trả lời chi tiết của admin gửi qua email
            $table->timestamps(); // Thời điểm tạo và cập nhật liên hệ phản hồi
        });
    }

    /**
     * Hủy bỏ bảng contacts khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
