<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tạo bảng nhật ký hoạt động tài khoản cho từng người dùng.
     */
    public function up(): void
    {
        Schema::create('account_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('type', 50)->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Xóa bảng nhật ký hoạt động tài khoản khi rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_activity_logs');
    }
};
