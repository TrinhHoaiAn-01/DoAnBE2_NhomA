<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng stock_histories.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Thẻ kho / Lịch sử biến động tồn kho (stock_histories)
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Liên kết sản phẩm bị thay đổi số lượng tồn kho (xóa sản phẩm thì xóa lịch sử thẻ kho)
            $table->enum('type', ['in', 'out'])->comment('in: Nhập, out: Xuất'); // Phân loại thay đổi (in = Nhập kho/Cân bằng tăng, out = Xuất kho/Bán hàng/Hao hụt)
            $table->integer('quantity'); // Số lượng sản phẩm biến động (luôn lưu giá trị dương)
            $table->string('reference_type')->nullable()->comment('receipt, issue, order, check'); // Loại chứng từ tham chiếu (receipt = phiếu nhập, issue = phiếu xuất, order = đơn hàng, check = kiểm kê)
            $table->string('reference_code')->nullable()->comment('Mã tham chiếu (vd: PN123, DH001)'); // Mã số của chứng từ tương ứng làm căn cứ đối soát
            $table->string('note')->nullable(); // Ghi chú diễn giải bổ sung lý do thay đổi tồn kho
            $table->timestamps(); // Thời điểm ghi nhận biến động
        });
    }

    /**
     * Hủy bỏ bảng stock_histories khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};
