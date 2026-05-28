<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Kết Nối Hàng Đợi Mặc Định (Default Queue Connection Name)
    |--------------------------------------------------------------------------
    |
    | Hàng đợi của Laravel hỗ trợ nhiều backend lưu trữ hàng đợi thông qua một API
    | hợp nhất và duy nhất, cung cấp cho bạn cú pháp lập trình giống nhau.
    | Kết nối hàng đợi mặc định được định nghĩa bên dưới.
    |
    */

    'default' => env('QUEUE_CONNECTION', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Các Kết Nối Hàng Đợi (Queue Connections)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể cấu hình các tùy chọn kết nối cho các dịch vụ hàng đợi
    | (queue backends) được sử dụng bởi ứng dụng của bạn. Các cấu hình mẫu đã được
    | thiết lập sẵn cho mỗi dịch vụ được hỗ trợ bởi Laravel.
    |
    | Các driver hỗ trợ: "sync", "database", "beanstalkd", "sqs", "redis", "null"
    |
    */

    'connections' => [

        // Chế độ đồng bộ (Sync): Các job được xử lý ngay lập tức trên luồng chính (không bất đồng bộ)
        'sync' => [
            'driver' => 'sync',
        ],

        // Hàng đợi lưu trong Cơ Sở Dữ Liệu (Database Queue)
        'database' => [
            'driver' => 'database',
            'connection' => env('DB_QUEUE_CONNECTION'),
            'table' => env('DB_QUEUE_TABLE', 'jobs'),
            'queue' => env('DB_QUEUE', 'default'),
            'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER', 90),
            'after_commit' => false,
        ],

        // Hàng đợi Beanstalkd
        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => env('BEANSTALKD_QUEUE_HOST', 'localhost'),
            'queue' => env('BEANSTALKD_QUEUE', 'default'),
            'retry_after' => (int) env('BEANSTALKD_QUEUE_RETRY_AFTER', 90),
            'block_for' => 0,
            'after_commit' => false,
        ],

        // Hàng đợi Amazon SQS (Simple Queue Service)
        'sqs' => [
            'driver' => 'sqs',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SQS_QUEUE', 'default'),
            'suffix' => env('SQS_SUFFIX'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'after_commit' => false,
        ],

        // Hàng đợi sử dụng Redis
        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_QUEUE_CONNECTION', 'default'),
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => (int) env('REDIS_QUEUE_RETRY_AFTER', 90),
            'block_for' => null,
            'after_commit' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Xử Lý Job Theo Lô (Job Batching)
    |--------------------------------------------------------------------------
    |
    | Các tùy chọn sau cấu hình cơ sở dữ liệu và bảng lưu trữ thông tin về các
    | job chạy theo lô (batching). Các tùy chọn này có thể trỏ tới bất kỳ kết nối
    | cơ sở dữ liệu và bảng nào đã được định nghĩa trong ứng dụng.
    |
    */

    'batching' => [
        'database' => env('DB_CONNECTION', 'sqlite'),
        'table' => 'job_batches',
    ],

    /*
    |--------------------------------------------------------------------------
    | Các Job Hàng Đợi Bị Lỗi (Failed Queue Jobs)
    |--------------------------------------------------------------------------
    |
    | Các cấu hình này quy định hành vi lưu trữ và ghi nhận lại các công việc (jobs)
    | trong hàng đợi bị thực hiện thất bại, cho phép bạn theo dõi và xử lý lại lỗi.
    |
    | Các driver hỗ trợ: "database-uuids", "dynamodb", "file", "null"
    |
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'sqlite'),
        'table' => 'failed_jobs',
    ],

];
