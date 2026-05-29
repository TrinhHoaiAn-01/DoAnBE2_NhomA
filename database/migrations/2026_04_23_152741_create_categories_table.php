<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng categories.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Danh mục sản phẩm (categories)
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->string('name'); // Tên danh mục (ví dụ: Thực phẩm, Đồ uống...)
            $table->string('slug')->unique(); // Slug tối ưu hóa tìm kiếm URL (Duy nhất)
            $table->string('icon')->default('fa-box'); // Icon đại diện danh mục (FontAwesome)
            $table->text('description')->nullable(); // Mô tả chi tiết danh mục
            $table->unsignedInteger('sort_order')->default(0); // Thứ tự sắp xếp hiển thị
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động (true = hiển thị, false = ẩn)
            $table->timestamps(); // Thời điểm tạo và cập nhật bản ghi
        });
    }

    /**
     * Hủy bỏ bảng categories khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
