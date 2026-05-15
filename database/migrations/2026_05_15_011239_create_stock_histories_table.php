<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out'])->comment('in: Nhập, out: Xuất');
            $table->integer('quantity');
            $table->string('reference_type')->nullable()->comment('receipt, issue, order, check');
            $table->string('reference_code')->nullable()->comment('Mã tham chiếu (vd: PN123, DH001)');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};
