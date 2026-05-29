<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng inventory_checks.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Phiếu kiểm kê kho hàng (inventory_checks)
        Schema::create('inventory_checks', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->string('code')->unique(); // Mã số phiếu kiểm kê duy nhất (ví dụ: KK-2026...)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Liên kết nhân viên thực hiện kiểm kho (Thủ kho/Kiểm toán)
            $table->text('note')->nullable(); // Ghi chú bổ sung
            $table->string('status')->default('completed'); // Trạng thái phiếu kiểm kê (completed = đã kiểm và cân bằng kho)
            $table->timestamps(); // Thời điểm tạo và cập nhật phiếu kiểm kê
        });
    }

    /**
     * Hủy bỏ bảng inventory_checks khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_checks');
    }
};
