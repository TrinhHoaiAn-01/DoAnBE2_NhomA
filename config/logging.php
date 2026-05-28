<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Kênh Ghi Log Mặc Định (Default Log Channel)
    |--------------------------------------------------------------------------
    |
    | Tùy chọn này định nghĩa kênh ghi log mặc định được sử dụng để lưu các thông
    | báo lỗi hoặc thông tin của hệ thống. Giá trị ở đây phải tương ứng với
    | một trong các kênh được cấu hình trong danh sách "channels" bên dưới.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Kênh Ghi Log Cho Các Tính Năng Bị Cảnh Báo Ngưng Hỗ Trợ (Deprecations)
    |--------------------------------------------------------------------------
    |
    | Tùy chọn này kiểm soát kênh ghi log được sử dụng để lưu trữ các cảnh báo
    | liên quan đến các tính năng PHP hoặc các thư viện không còn được hỗ trợ.
    | Điều này giúp chuẩn bị nâng cấp ứng dụng cho các phiên bản tiếp theo.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Các Kênh Ghi Log (Log Channels)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể cấu hình các kênh ghi log cho ứng dụng của mình. Laravel
    | sử dụng thư viện ghi log Monolog của PHP, tích hợp sẵn nhiều handler và
    | formatter ghi log mạnh mẽ để bạn sử dụng.
    |
    | Các driver khả dụng: "single", "daily", "slack", "syslog",
    |                     "errorlog", "monolog", "custom", "stack"
    |
    */

    'channels' => [

        // Kênh Stack: Kết hợp nhiều kênh log lại với nhau
        'stack' => [
            'driver' => 'stack',
            'channels' => explode(',', env('LOG_STACK', 'single')),
            'ignore_exceptions' => false,
        ],

        // Kênh Single: Ghi log vào một tệp duy nhất
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        // Kênh Daily: Ghi log chia theo ngày, tự động dọn dẹp các tệp cũ
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => env('LOG_DAILY_DAYS', 14),
            'replace_placeholders' => true,
        ],

        // Kênh Slack: Gửi cảnh báo log trực tiếp tới kênh Slack qua Webhook
        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => env('LOG_SLACK_USERNAME', 'Laravel Log'),
            'emoji' => env('LOG_SLACK_EMOJI', ':boom:'),
            'level' => env('LOG_LEVEL', 'critical'),
            'replace_placeholders' => true,
        ],

        // Kênh Papertrail: Ghi log từ xa qua dịch vụ Papertrail
        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        // Kênh Stderr: Xuất log ra lỗi tiêu chuẩn hệ thống (thường dùng cho Docker)
        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        // Kênh Syslog: Ghi log vào dịch vụ nhật ký hệ thống của hệ điều hành
        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
            'facility' => env('LOG_SYSLOG_FACILITY', LOG_USER),
            'replace_placeholders' => true,
        ],

        // Kênh Errorlog: Ghi log vào trình xử lý log mặc định của PHP/Apache/Nginx
        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        // Kênh Null: Không làm gì cả (bỏ qua log hoàn toàn)
        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        // Kênh Emergency: Kênh ghi lỗi khẩn cấp dự phòng của Laravel
        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

    ],

];
