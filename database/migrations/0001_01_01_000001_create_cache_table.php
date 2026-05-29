<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc các bảng cache và cache_locks.
     *
     * @return void
     */
    public function up(): void
    {
        // Bảng lưu trữ giá trị cache (cache)
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary(); // Khóa cache (Khóa chính)
            $table->mediumText('value'); // Giá trị cache lưu trữ
            $table->integer('expiration'); // Thời điểm hết hạn của cache (UNIX Timestamp)
        });

        // Bảng lưu trữ trạng thái khóa cache (cache_locks)
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary(); // Khóa lock (Khóa chính)
            $table->string('owner'); // Đối tượng sở hữu khóa lock
            $table->integer('expiration'); // Thời điểm hết hạn khóa lock (UNIX Timestamp)
        });
    }

    /**
     * Hủy bỏ các bảng cache và cache_locks khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
