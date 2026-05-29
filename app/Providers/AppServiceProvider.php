<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

/**
 * Lớp AppServiceProvider
 *
 * Đăng ký các dịch vụ hệ thống và cấu hình khởi động cho toàn bộ ứng dụng Laravel.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Đăng ký bất kỳ dịch vụ ứng dụng nào (Application Services).
     * Được chạy trước khi tất cả các Service Provider khác khởi động.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Khởi động (Bootstrap) bất kỳ dịch vụ ứng dụng nào.
     * Được chạy sau khi tất cả các dịch vụ đã được đăng ký thành công.
     *
     * @return void
     */
    public function boot(): void
    {
        // Thiết lập độ dài mặc định cho các cột chuỗi trong database là 191 ký tự 
        // để hỗ trợ các phiên bản hệ quản trị CSDL cũ (như MySQL cũ) tránh lỗi tạo chỉ mục (index key length)
        Schema::defaultStringLength(191);
    }
}

