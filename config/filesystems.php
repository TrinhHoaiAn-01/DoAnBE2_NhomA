<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Đĩa Lưu Trữ Mặc Định (Default Filesystem Disk)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể chỉ định đĩa lưu trữ (filesystem disk) mặc định sẽ được
    | sử dụng bởi framework. Đĩa "local" cũng như nhiều loại đĩa đám mây (cloud)
    | khác luôn sẵn sàng cho ứng dụng của bạn lưu trữ tệp.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Các Đĩa Lưu Trữ (Filesystem Disks)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể cấu hình bao nhiêu đĩa tùy ý và thậm chí cấu hình nhiều
    | đĩa cho cùng một driver. Các ví dụ về hầu hết các driver lưu trữ được hỗ trợ
    | đều được cấu hình ở đây để tham khảo.
    |
    | Các driver được hỗ trợ: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        // Đĩa lưu trữ cục bộ, riêng tư (Private Local Disk)
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        // Đĩa lưu trữ công khai (Public Local Disk), có thể truy cập qua URL
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        // Đĩa lưu trữ đám mây Amazon S3
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Liên Kết Ký Hiệu (Symbolic Links)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể cấu hình các liên kết ký hiệu (symlinks) được tạo ra
    | khi lệnh Artisan `storage:link` được thực thi. Khóa của mảng sẽ là vị trí
    | đường dẫn liên kết công khai và giá trị là thư mục đích thực tế.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
