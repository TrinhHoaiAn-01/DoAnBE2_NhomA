<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng warehouse_issues.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Phiếu xuất kho hủy / xuất kho do hao hụt (warehouse_issues)
        Schema::create('warehouse_issues', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->string('code')->unique(); // Mã số phiếu xuất kho duy nhất (ví dụ: PX-2026...)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Liên kết người lập phiếu (Nhân viên kho)
            $table->string('reason')->comment('Lý do xuất kho (bán hàng, bảo hành, hao hụt, khác...)'); // Lý do xuất chi tiết (hao hụt, hư hỏng, quá hạn...)
            $table->text('note')->nullable(); // Ghi chú bổ sung
            $table->string('status')->default('completed'); // Trạng thái phiếu xuất (mặc định completed = đã xuất)
            $table->timestamps(); // Thời điểm tạo và cập nhật phiếu xuất kho
        });
    }

    /**
     * Hủy bỏ bảng warehouse_issues khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_issues');
    }
};
