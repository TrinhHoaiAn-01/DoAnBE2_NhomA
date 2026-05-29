<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng warehouse_issue_items.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Chi tiết phiếu xuất kho (warehouse_issue_items)
        Schema::create('warehouse_issue_items', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->foreignId('warehouse_issue_id')->constrained()->onDelete('cascade'); // Liên kết phiếu xuất kho (xóa phiếu xuất thì xóa chi tiết tương ứng)
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Liên kết sản phẩm bị xuất kho (xóa sản phẩm thì xóa chi tiết tương ứng)
            $table->integer('quantity'); // Số lượng sản phẩm xuất kho hủy/hao hụt
            $table->timestamps(); // Thời điểm tạo và cập nhật bản ghi
        });
    }

    /**
     * Hủy bỏ bảng warehouse_issue_items khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_issue_items');
    }
};
