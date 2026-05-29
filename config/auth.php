<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cấu Hình Xác Thực Mặc Định (Authentication Defaults)
    |--------------------------------------------------------------------------
    |
    | Tùy chọn này xác định "guard" xác thực mặc định và bộ môi giới khôi phục
    | mật khẩu (password reset broker) cho ứng dụng của bạn. Bạn có thể thay
    | đổi các giá trị này tùy theo yêu cầu hệ thống.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Bộ Bảo Vệ Xác Thực (Authentication Guards)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể định nghĩa các bộ bảo vệ (guards) xác thực cho ứng dụng.
    | Cấu hình mặc định sử dụng session storage và nhà cung cấp người dùng Eloquent.
    |
    | Tất cả các guard xác thực đều cần một user provider để xác định cách người dùng
    | được truy xuất từ cơ sở dữ liệu hoặc hệ thống lưu trữ khác.
    |
    | Hỗ trợ: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Nhà Cung Cấp Người Dùng (User Providers)
    |--------------------------------------------------------------------------
    |
    | Nhà cung cấp người dùng (user providers) định nghĩa cách người dùng được
    | truy xuất từ cơ sở dữ liệu của bạn. Eloquent thường là lựa chọn phổ biến nhất.
    |
    | Nếu bạn có nhiều bảng hoặc model người dùng khác nhau, bạn có thể cấu hình
    | nhiều provider tương ứng để liên kết với các guard xác thực khác nhau.
    |
    | Hỗ trợ: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Khôi Phục Mật Khẩu (Resetting Passwords)
    |--------------------------------------------------------------------------
    |
    | Các cấu hình này quy định hành vi của tính năng khôi phục mật khẩu của Laravel,
    | bao gồm bảng lưu trữ token khôi phục và user provider được gọi để lấy thông tin.
    |
    | Thời gian hết hạn (expire) là số phút token khôi phục có hiệu lực (tính bảo mật).
    | Giới hạn tần suất (throttle) ngăn chặn việc gửi yêu cầu khôi phục liên tục.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Thời Gian Chờ Xác Nhận Mật Khẩu (Password Confirmation Timeout)
    |--------------------------------------------------------------------------
    |
    | Định nghĩa khoảng thời gian (tính bằng giây) trước khi cửa sổ xác nhận mật khẩu
    | hết hạn và người dùng phải nhập lại mật khẩu trên màn hình xác nhận.
    | Mặc định là 3 giờ (10800 giây).
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
