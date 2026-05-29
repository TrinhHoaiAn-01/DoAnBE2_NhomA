<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng warehouse_receipts.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Phiếu nhập kho (warehouse_receipts)
        Schema::create('warehouse_receipts', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->string('code')->unique(); // Mã số phiếu nhập kho duy nhất (ví dụ: WR-2026...)
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade'); // Liên kết Nhà cung cấp hàng hóa
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Liên kết Nhân viên tạo phiếu nhập (Thủ kho)
            $table->decimal('total_amount', 15, 2)->default(0); // Tổng giá trị hàng hóa nhập kho của phiếu
            $table->text('note')->nullable(); // Ghi chú bổ sung
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); // Trạng thái phiếu nhập (pending = chờ phê duyệt, completed = đã hoàn thành, cancelled = đã hủy)
            $table->timestamps(); // Thời điểm tạo và cập nhật phiếu nhập
        });
    }

    /**
     * Hủy bỏ bảng warehouse_receipts khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_receipts');
    }
};
