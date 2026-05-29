<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng warehouse_receipt_items.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Chi tiết phiếu nhập kho (warehouse_receipt_items)
        Schema::create('warehouse_receipt_items', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->foreignId('warehouse_receipt_id')->constrained('warehouse_receipts')->onDelete('cascade'); // Liên kết phiếu nhập kho (xóa phiếu nhập thì xóa chi tiết tương ứng)
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Liên kết sản phẩm nhập kho (xóa sản phẩm thì xóa chi tiết nhập tương ứng)
            $table->integer('quantity'); // Số lượng sản phẩm nhập kho
            $table->decimal('price', 15, 2); // Đơn giá nhập của sản phẩm
            $table->decimal('subtotal', 15, 2); // Thành tiền dòng nhập (quantity * price)
            $table->timestamps(); // Thời điểm tạo và cập nhật bản ghi
        });
    }

    /**
     * Hủy bỏ bảng warehouse_receipt_items khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_receipt_items');
    }
};
