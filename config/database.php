<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Tên Kết Nối Cơ Sở Dữ Liệu Mặc Định (Default Database Connection Name)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể chỉ định kết nối cơ sở dữ liệu nào dưới đây bạn muốn
    | sử dụng làm kết nối mặc định cho các thao tác cơ sở dữ liệu. Kết nối này
    | sẽ được sử dụng trừ khi một kết nối khác được chỉ định cụ thể.
    |
    */

    'default' => env('DB_CONNECTION', 'sqlite'),

    /*
    |--------------------------------------------------------------------------
    | Các Kết Nối Cơ Sở Dữ Liệu (Database Connections)
    |--------------------------------------------------------------------------
    |
    | Dưới đây là tất cả các kết nối cơ sở dữ liệu được định nghĩa cho ứng dụng.
    | Một cấu hình ví dụ được cung cấp cho mỗi hệ quản trị cơ sở dữ liệu
    | được hỗ trợ bởi Laravel. Bạn có thể tự do thêm / bớt kết nối.
    |
    */

    'connections' => [

        // Cấu hình kết nối cơ sở dữ liệu SQLite
        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
        ],

        // Cấu hình kết nối cơ sở dữ liệu MySQL
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // Cấu hình kết nối cơ sở dữ liệu MariaDB
        'mariadb' => [
            'driver' => 'mariadb',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // Cấu hình kết nối cơ sở dữ liệu PostgreSQL
        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        // Cấu hình kết nối cơ sở dữ liệu SQL Server (Microsoft)
        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Bảng Lưu Trực Trạng Thái Migration (Migration Repository Table)
    |--------------------------------------------------------------------------
    |
    | Bảng này ghi nhận lại tất cả các migration đã được chạy cho ứng dụng của bạn.
    | Sử dụng thông tin này, chúng tôi có thể xác định migration nào trên đĩa
    | chưa thực sự được chạy dưới cơ sở dữ liệu.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cơ Sở Dữ Liệu Redis (Redis Databases)
    |--------------------------------------------------------------------------
    |
    | Redis là một bộ lưu trữ key-value mã nguồn mở, tốc độ cao và nâng cao,
    | cung cấp tập lệnh phong phú hơn so với các hệ thống key-value thông thường.
    | Bạn có thể định nghĩa cấu hình kết nối Redis của mình tại đây.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        // Kết nối Redis mặc định dùng cho việc lưu trữ dữ liệu
        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        // Kết nối Redis dành riêng cho hệ thống Cache
        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
