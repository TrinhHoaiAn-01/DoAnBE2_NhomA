<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi thêm các cột quản lý lô hàng và hạn sử dụng vào bảng warehouse_receipt_items.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('warehouse_receipt_items', function (Blueprint $table) {
            // Thêm mã lô sản xuất (batch_code) sau cột thành tiền
            $table->string('batch_code')->nullable()->after('subtotal');
            // Thêm ngày hết hạn (expires_at) sau cột mã lô
            $table->date('expires_at')->nullable()->after('batch_code');
        });
    }

    /**
     * Thu hồi các cột lô hàng và hạn sử dụng khỏi bảng warehouse_receipt_items khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('warehouse_receipt_items', function (Blueprint $table) {
            $table->dropColumn(['batch_code', 'expires_at']);
        });
    }
};
