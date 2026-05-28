<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng order_items.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Chi tiết đơn hàng (order_items)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->foreignId('order_id')->constrained()->cascadeOnDelete(); // Khóa ngoại liên kết bảng orders (xóa đơn thì xóa chi tiết đơn tương ứng)
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete(); // Khóa ngoại liên kết bảng products (nếu sản phẩm bị xóa, giữ nguyên lịch sử giao dịch và đặt null)
            $table->string('product_name'); // Tên sản phẩm lưu lại tại thời điểm mua (để tránh thay đổi tên sản phẩm ảnh hưởng lịch sử hóa đơn)
            $table->string('sku')->nullable(); // Mã sản phẩm SKU lưu trữ lịch sử
            $table->decimal('price', 12, 2); // Giá sản phẩm tại thời điểm mua
            $table->unsignedInteger('quantity'); // Số lượng sản phẩm mua
            $table->decimal('subtotal', 12, 2); // Thành tiền (price * quantity)
            $table->timestamps(); // Thời điểm tạo và cập nhật bản ghi
        });
    }

    /**
     * Hủy bỏ bảng order_items khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
