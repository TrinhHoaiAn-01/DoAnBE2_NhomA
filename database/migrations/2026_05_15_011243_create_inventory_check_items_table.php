<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng inventory_check_items.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Chi tiết phiếu kiểm kê kho hàng (inventory_check_items)
        Schema::create('inventory_check_items', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->foreignId('inventory_check_id')->constrained()->onDelete('cascade'); // Liên kết phiếu kiểm kê (xóa phiếu thì xóa chi tiết tương ứng)
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Liên kết sản phẩm bị kiểm kho (xóa sản phẩm thì xóa chi tiết tương ứng)
            $table->integer('old_stock'); // Số lượng tồn kho trên sổ sách hệ thống trước khi kiểm kê
            $table->integer('actual_stock'); // Số lượng sản phẩm thực tế kiểm đếm được trong kho
            $table->integer('difference'); // Chênh lệch (actual_stock - old_stock, có thể âm hoặc dương)
            $table->text('note')->nullable(); // Ghi chú giải thích cho sự chênh lệch của dòng sản phẩm này
            $table->timestamps(); // Thời điểm ghi nhận
        });
    }

    /**
     * Hủy bỏ bảng inventory_check_items khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_check_items');
    }
};
