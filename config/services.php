<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Các Dịch Vụ Của Bên Thứ Ba (Third Party Services)
    |--------------------------------------------------------------------------
    |
    | Tệp tin này dùng để lưu trữ thông tin xác thực/kết nối cho các dịch vụ
    | của bên thứ ba như Mailgun, Postmark, AWS, Slack và nhiều dịch vụ khác.
    | Cung cấp một nơi tập trung và quy chuẩn để các gói thư viện dễ dàng truy xuất.
    |
    */

    // Dịch vụ Postmark gửi email
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    // Dịch vụ Amazon SES gửi email
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Dịch vụ Resend gửi email
    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    // Dịch vụ gửi thông báo qua Slack
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
