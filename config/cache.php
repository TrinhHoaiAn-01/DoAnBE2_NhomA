<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Bộ Lưu Trữ Cache Mặc Định (Default Cache Store)
    |--------------------------------------------------------------------------
    |
    | Tùy chọn này điều khiển bộ lưu trữ cache mặc định sẽ được sử dụng bởi
    | framework. Kết nối này sẽ được dùng nếu một kết nối khác không được
    | chỉ định cụ thể khi thực hiện thao tác cache trong ứng dụng.
    |
    */

    'default' => env('CACHE_STORE', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Các Bộ Lưu Trữ Cache (Cache Stores)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể định nghĩa tất cả các bộ lưu trữ cache cho ứng dụng của
    | bạn cùng với driver tương ứng. Bạn cũng có thể định nghĩa nhiều bộ lưu trữ
    | cho cùng một driver cache để phân nhóm dữ liệu lưu trữ.
    |
    | Các driver hỗ trợ: "array", "database", "file", "memcached",
    |                    "redis", "dynamodb", "octane", "null"
    |
    */

    'stores' => [

        // Cache mảng (chỉ lưu trữ trong bộ nhớ tạm của request hiện tại)
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        // Cache cơ sở dữ liệu
        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CACHE_CONNECTION'),
            'table' => env('DB_CACHE_TABLE', 'cache'),
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
            'lock_table' => env('DB_CACHE_LOCK_TABLE'),
        ],

        // Cache tệp tin (File Cache)
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],

        // Cache Memcached
        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        // Cache Redis
        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_CACHE_CONNECTION', 'cache'),
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
        ],

        // Cache DynamoDB (AWS)
        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

        // Cache Octane (Sử dụng với Laravel Octane)
        'octane' => [
            'driver' => 'octane',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Tiền Tố Khóa Cache (Cache Key Prefix)
    |--------------------------------------------------------------------------
    |
    | Khi sử dụng các bộ lưu trữ cache dùng chung như database, Redis, Memcached,
    | có thể có các ứng dụng khác cũng sử dụng chung máy chủ cache đó.
    | Tiền tố này giúp tránh xung đột khóa giữa các ứng dụng.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_'),

];
