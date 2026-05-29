<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng products.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Sản phẩm (products)
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->foreignId('category_id')->constrained()->cascadeOnDelete(); // Khóa ngoại liên kết danh mục (xóa danh mục thì xóa sản phẩm liên quan)
            $table->string('name'); // Tên sản phẩm
            $table->string('slug')->unique(); // Slug tối ưu hóa URL (Duy nhất)
            $table->string('sku')->unique(); // Mã quản lý sản phẩm SKU (Duy nhất)
            $table->string('brand')->nullable(); // Thương hiệu sản xuất
            $table->text('description')->nullable(); // Mô tả chi tiết sản phẩm
            $table->decimal('price', 12, 2); // Giá bán thực tế hiện tại
            $table->decimal('original_price', 12, 2)->nullable(); // Giá gốc trước giảm (nếu có)
            $table->unsignedInteger('stock')->default(0); // Số lượng tồn kho hiện tại
            $table->string('image_url')->nullable(); // Đường dẫn hình ảnh minh họa sản phẩm
            $table->boolean('is_active')->default(true); // Trạng thái mở bán (true = hoạt động, false = ẩn)
            $table->timestamps(); // Thời điểm tạo và cập nhật bản ghi
        });
    }

    /**
     * Hủy bỏ bảng products khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
