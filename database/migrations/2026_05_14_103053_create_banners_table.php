<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng banners.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Banner quảng cáo (banners)
        Schema::create('banners', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->string('title'); // Tiêu đề của banner quảng cáo
            $table->string('image_url'); // Đường dẫn tệp hình ảnh banner trong storage
            $table->string('link')->nullable(); // Đường dẫn liên kết khi nhấn vào banner (URL)
            $table->string('position')->default('main_slider'); // Vị trí hiển thị banner trên giao diện (ví dụ: main_slider, sidebar...)
            $table->integer('sort_order')->default(0); // Thứ tự hiển thị ưu tiên
            $table->boolean('is_active')->default(true); // Trạng thái hiển thị (true = hoạt động, false = ẩn)
            $table->date('start_date')->nullable(); // Ngày bắt đầu có hiệu lực hiển thị
            $table->date('end_date')->nullable(); // Ngày hết hạn hiệu lực hiển thị
            $table->timestamps(); // Thời điểm tạo và cập nhật banner
        });
    }

    /**
     * Hủy bỏ bảng banners khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
