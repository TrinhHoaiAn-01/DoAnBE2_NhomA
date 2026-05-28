<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi thêm các cột hồ sơ cá nhân còn thiếu vào bảng users nếu chưa tồn tại.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kiểm tra và thêm cột username nếu chưa có
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('name');
            }
            // Kiểm tra và thêm cột home_address nếu chưa có
            if (!Schema::hasColumn('users', 'home_address')) {
                $table->text('home_address')->nullable()->after('avatar_url');
            }
            // Kiểm tra và thêm cột gender nếu chưa có
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable()->after('home_address');
            }
            // Kiểm tra và thêm cột date_of_birth nếu chưa có
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('gender');
            }
        });
    }

    /**
     * Thu hồi các cột hồ sơ cá nhân khỏi bảng users khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'home_address', 'gender', 'date_of_birth']);
        });
    }
};
