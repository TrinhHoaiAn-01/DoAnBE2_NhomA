<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_code')->unique(); // Mã phiếu nhập (VD: PN001)
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete(); // NCC
            $table->string('created_by'); // Người tạo phiếu
            $table->decimal('total_amount', 15, 2)->default(0); // Tổng tiền
            $table->text('note')->nullable(); // Ghi chú
            $table->string('status')->default('pending'); // pending, approved, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_receipts');
    }
};
