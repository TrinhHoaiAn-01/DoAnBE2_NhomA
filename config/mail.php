<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trình Gửi Thư Mặc Định (Default Mailer)
    |--------------------------------------------------------------------------
    |
    | Tùy chọn này điều khiển bộ gửi thư (mailer) mặc định sẽ được sử dụng để
    | gửi tất cả các email, trừ khi một bộ gửi thư khác được chỉ định cụ thể.
    | Các cấu hình bộ gửi thư bổ sung nằm trong mảng "mailers" dưới đây.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Cấu Hình Trình Gửi Thư (Mailer Configurations)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể cấu hình tất cả các bộ gửi thư được sử dụng bởi ứng dụng
    | cùng với các cài đặt tương ứng. Một số ví dụ đã được cấu hình sẵn.
    |
    | Laravel hỗ trợ nhiều driver gửi thư khác nhau (mail transport). Bạn có thể
    | cấu hình chi tiết cho từng loại tùy thuộc vào nhu cầu của hệ thống.
    |
    | Các driver hỗ trợ: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |                    "postmark", "resend", "log", "array",
    |                    "failover", "roundrobin"
    |
    */

    'mailers' => [

        // Cấu hình gửi thư qua SMTP
        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        // Cấu hình gửi thư qua dịch vụ Amazon SES
        'ses' => [
            'transport' => 'ses',
        ],

        // Cấu hình gửi thư qua Postmark
        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        // Cấu hình gửi thư qua dịch vụ Resend
        'resend' => [
            'transport' => 'resend',
        ],

        // Cấu hình gửi thư qua Sendmail tích hợp sẵn trên server Linux
        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        // Ghi log email thay vì gửi thực tế (thường dùng trong môi trường phát triển)
        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        // Lưu email vào mảng bộ nhớ (dùng cho mục đích kiểm thử/testing)
        'array' => [
            'transport' => 'array',
        ],

        // Dự phòng (Failover): Tự động chuyển đổi sang kênh log nếu SMTP lỗi
        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],

        // Phân phối Roundrobin: Chia đều tải gửi email giữa SES và Postmark
        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Địa Chỉ Gửi Mặc Định Toàn Cục (Global "From" Address)
    |--------------------------------------------------------------------------
    |
    | Bạn có thể muốn tất cả các email gửi từ ứng dụng sử dụng chung một địa chỉ
    | người gửi cố định. Tại đây bạn có thể chỉ định tên và địa chỉ người gửi
    | mặc định toàn cục cho ứng dụng của mình.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

];
