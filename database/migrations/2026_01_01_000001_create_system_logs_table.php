<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng system_logs.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Nhật ký hoạt động hệ thống (system_logs)
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->string('user_name'); // Tên tài khoản hoặc định danh người thực hiện hành động
            $table->string('action'); // Tên hành động (ví dụ: Update Permission, Approve Import...)
            $table->string('target_type'); // Phân loại đối tượng chịu tác động (ví dụ: Role, Product...)
            $table->json('old_data')->nullable(); // Ảnh chụp trạng thái dữ liệu cũ trước khi thực hiện (dạng JSON)
            $table->json('new_data')->nullable(); // Ảnh chụp trạng thái dữ liệu mới sau khi thực hiện (dạng JSON)
            $table->timestamps(); // Thời điểm tạo bản ghi nhật ký (ghi nhận chính xác thời gian hành động)
        });
    }

    /**
     * Hủy bỏ bảng system_logs khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
