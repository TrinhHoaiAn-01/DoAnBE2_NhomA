<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi thêm cột role_id vào bảng users nếu chưa tồn tại.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            // Kiểm tra và thêm cột role_id sau cột password
            if (! Schema::hasColumn('users', 'role_id')) {
                $table->unsignedTinyInteger('role_id')->default(2)->after('password');
            }
        });
    }

    /**
     * Thu hồi cột role_id khỏi bảng users khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            // Kiểm tra và hủy cột role_id
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropColumn('role_id');
            }
        });
    }
};
