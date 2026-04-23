<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_receipt_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->constrained('warehouse_receipts')->cascadeOnDelete();
            $table->unsignedBigInteger('product_id'); // Liên kết tới bảng products của Người 2
            $table->integer('quantity'); // Số lượng nhập
            $table->decimal('unit_price', 15, 2); // Giá nhập 1 SP
            $table->decimal('total_price', 15, 2); // Thành tiền
            $table->string('batch_number')->nullable(); // Số lô hàng
            $table->date('expiry_date')->nullable(); // Hạn sử dụng
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_receipt_details');
    }
};
