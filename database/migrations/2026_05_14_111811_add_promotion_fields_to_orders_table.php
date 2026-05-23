<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->foreignId('promotion_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->string('promotion_code')->nullable()->after('note');
            $table->decimal('discount_total', 12, 2)->default(0)->after('shipping_fee');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('promotion_id');
            $table->dropColumn(['promotion_code', 'discount_total']);
        });
    }
};
