<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng orders.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Đơn hàng (orders)
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Liên kết tài khoản mua hàng (nếu có, xóa tài khoản thì giữ nguyên đơn và đặt null)
            $table->string('code')->unique(); // Mã số đơn hàng duy nhất (ví dụ: ORD-2026...)
            $table->string('customer_name'); // Tên người nhận hàng
            $table->string('customer_email')->nullable(); // Email liên hệ nhận hàng
            $table->string('customer_phone'); // Số điện thoại nhận hàng
            $table->string('shipping_address'); // Địa chỉ giao hàng chi tiết
            $table->text('note')->nullable(); // Ghi chú giao nhận của khách hàng
            $table->string('payment_method')->default('cod'); // Phương thức thanh toán (cod, bank_transfer...)
            $table->string('payment_status')->default('pending'); // Trạng thái thanh toán (pending, paid, refunded...)
            $table->string('status')->default('pending'); // Trạng thái đơn hàng (pending, processing, shipping, completed, cancelled)
            $table->decimal('subtotal', 12, 2)->default(0); // Tổng tiền hàng trước phí/giảm giá
            $table->decimal('shipping_fee', 12, 2)->default(0); // Phí vận chuyển áp dụng
            $table->decimal('total', 12, 2)->default(0); // Tổng tiền khách phải trả thực tế (subtotal + shipping_fee)
            $table->timestamps(); // Thời điểm đặt hàng và cập nhật đơn hàng
        });
    }

    /**
     * Hủy bỏ bảng orders khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
