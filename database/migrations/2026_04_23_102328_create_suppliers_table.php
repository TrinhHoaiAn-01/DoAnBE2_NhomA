<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng suppliers.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Nhà cung cấp (suppliers)
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->string('name'); // Tên nhà cung cấp
            $table->string('phone')->nullable(); // Số điện thoại liên hệ
            $table->string('address')->nullable(); // Địa chỉ nhà cung cấp
            $table->timestamps(); // Thời điểm tạo và cập nhật bản ghi
        });
    }

    /**
     * Hủy bỏ bảng suppliers khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
