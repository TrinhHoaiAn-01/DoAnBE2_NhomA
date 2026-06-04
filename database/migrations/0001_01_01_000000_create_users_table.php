<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc các bảng users, password_reset_tokens, và sessions.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Người dùng (users)
        Schema::create('users', function (Blueprint $table) {

            $table->id(); // Khóa chính tự sinh

            // Thông tin cá nhân
            $table->string('name'); // Họ và tên

            $table->string('username')->unique(); // Tên đăng nhập (Duy nhất)

            $table->string('email')->unique(); // Địa chỉ Email (Duy nhất)

            $table->string('phone')->nullable(); // Số điện thoại

            $table->string('avatar_url')->nullable(); // Đường dẫn ảnh đại diện

            $table->text('home_address')->nullable(); // Địa chỉ nhà

            // Giới tính (male, female, other)
            $table->string('gender')->nullable();

            $table->date('date_of_birth')->nullable(); // Ngày sinh

            // Trạng thái tài khoản (true = hoạt động, false = bị khóa)
            $table->boolean('status')->default(true);

            // Vai trò người dùng (ROLE_1 đến ROLE_5)
            $table->integer('role_id')->default(2);

            // Thông tin xác thực & mật khẩu
            $table->timestamp('email_verified_at')->nullable(); // Thời điểm xác minh email

            $table->string('password'); // Mật khẩu đã mã hóa

            $table->rememberToken(); // Token ghi nhớ đăng nhập

            $table->timestamps(); // Thời điểm tạo và cập nhật bản ghi

        });

        // Tạo bảng lưu mã khôi phục mật khẩu (password_reset_tokens)
        Schema::create('password_reset_tokens', function (Blueprint $table) {

            $table->string('email')->primary(); // Email người nhận mã (Khóa chính)

            $table->string('token'); // Token bảo mật dùng để xác thực khôi phục

            $table->timestamp('created_at')->nullable(); // Thời điểm tạo token

        });

        // Tạo bảng lưu phiên làm việc của người dùng (sessions)
        Schema::create('sessions', function (Blueprint $table) {

            $table->string('id')->primary(); // ID phiên làm việc (Khóa chính)

            $table->foreignId('user_id')->nullable()->index(); // Liên kết với người dùng (Khóa ngoại)

            $table->string('ip_address', 45)->nullable(); // Địa chỉ IP của máy truy cập

            $table->text('user_agent')->nullable(); // Thông tin trình duyệt/thiết bị truy cập

            $table->longText('payload'); // Dữ liệu phiên lưu trữ dưới dạng chuỗi hóa

            $table->integer('last_activity')->index(); // Thời điểm hoạt động cuối cùng

        });
    }

    /**
     * Hủy bỏ các bảng users, password_reset_tokens và sessions khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');

        Schema::dropIfExists('password_reset_tokens');

        Schema::dropIfExists('sessions');
    }
};