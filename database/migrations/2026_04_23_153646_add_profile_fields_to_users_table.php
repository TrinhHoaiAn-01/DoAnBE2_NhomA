<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi thêm các cột phone, status, và avatar_url vào bảng users nếu chưa tồn tại.
     *
     * @return void
     */
    public function up(): void
	{
		Schema::table('users', function (Blueprint $table) {

			// Kiểm tra và thêm cột phone nếu chưa có
			if (!Schema::hasColumn('users', 'phone')) {
				$table->string('phone', 20)->nullable()->after('email');
			}

			// Kiểm tra và thêm cột status nếu chưa có
			if (!Schema::hasColumn('users', 'status')) {
				$table->string('status', 50)->default('active')->after('password');
			}

			// Kiểm tra và thêm cột avatar_url nếu chưa có
			if (!Schema::hasColumn('users', 'avatar_url')) {
				$table->string('avatar_url')->nullable()->after('status');
			}

		});
	}

    /**
     * Thu hồi các cột phone, status, và avatar_url khỏi bảng users khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'status', 'avatar_url']);
        });
    }
};
