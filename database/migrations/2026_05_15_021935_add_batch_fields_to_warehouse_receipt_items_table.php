<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_receipt_items', function (Blueprint $table) {
            $table->string('batch_code')->nullable()->after('subtotal');
            $table->date('expires_at')->nullable()->after('batch_code');
        });
    }

    public function down(): void
    {
        Schema::table('warehouse_receipt_items', function (Blueprint $table) {
            $table->dropColumn(['batch_code', 'expires_at']);
        });
    }
};
