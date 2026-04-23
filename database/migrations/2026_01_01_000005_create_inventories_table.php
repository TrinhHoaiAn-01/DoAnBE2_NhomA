<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // Liên kết sản phẩm (Người 2)
            $table->string('batch_number')->nullable(); // Lô hàng (Để biết nhập đợt nào)
            $table->date('expiry_date')->nullable(); // Hạn sử dụng (Để cảnh báo)
            $table->integer('quantity')->default(0); // Số lượng tồn thực tế
            $table->timestamps();
            
            // Đảm bảo không trùng lặp lô hàng cho cùng 1 sản phẩm
            $table->unique(['product_id', 'batch_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
