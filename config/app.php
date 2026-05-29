<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tên Ứng Dụng (Application Name)
    |--------------------------------------------------------------------------
    |
    | Giá trị này là tên ứng dụng của bạn, được sử dụng khi framework cần hiển thị
    | tên ứng dụng trong các thông báo hoặc các thành phần giao diện người dùng khác.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Môi Trường Ứng Dụng (Application Environment)
    |--------------------------------------------------------------------------
    |
    | Giá trị này quyết định "môi trường" hiện tại ứng dụng của bạn đang chạy.
    | Cấu hình này có thể thay đổi cách bạn thiết lập các dịch vụ khác nhau
    | mà ứng dụng sử dụng. Thiết lập giá trị này trong tệp ".env".
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Chế Độ Kiểm Lỗi Ứng Dụng (Application Debug Mode)
    |--------------------------------------------------------------------------
    |
    | Khi ứng dụng ở chế độ debug, các thông báo lỗi chi tiết cùng với stack trace
    | sẽ được hiển thị khi xảy ra lỗi. Nếu bị vô hiệu hóa, một trang lỗi chung chung
    | đơn giản sẽ được hiển thị.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | URL Ứng Dụng (Application URL)
    |--------------------------------------------------------------------------
    |
    | URL này được sử dụng bởi console để tạo ra các liên kết (URL) chính xác
    | khi sử dụng công cụ dòng lệnh Artisan. Bạn nên đặt giá trị này khớp với
    | URL gốc của ứng dụng.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Múi Giờ Ứng Dụng (Application Timezone)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể chỉ định múi giờ mặc định cho ứng dụng, múi giờ này
    | sẽ được sử dụng bởi các hàm định dạng ngày và giờ của PHP.
    | Múi giờ mặc định được đặt là "UTC".
    |
    */

    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Cấu Hình Ngôn Ngữ Ứng Dụng (Application Locale Configuration)
    |--------------------------------------------------------------------------
    |
    | Ngôn ngữ ứng dụng quyết định ngôn ngữ mặc định sẽ được sử dụng bởi
    | các phương thức dịch thuật / đa ngôn ngữ của Laravel. Có thể đặt thành
    | bất kỳ ngôn ngữ nào mà ứng dụng hỗ trợ.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    // Ngôn ngữ dự phòng khi ngôn ngữ hiện tại không có bản dịch
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    // Ngôn ngữ mặc định cho thư viện tạo dữ liệu giả Faker
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Khóa Mã Hóa (Encryption Key)
    |--------------------------------------------------------------------------
    |
    | Khóa này được sử dụng bởi các dịch vụ mã hóa của Laravel và phải được đặt
    | là một chuỗi ngẫu nhiên 32 ký tự để đảm bảo tính bảo mật cho tất cả các
    | giá trị được mã hóa. Hãy thiết lập trước khi triển khai ứng dụng.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Trình Quản Lý Chế Độ Bảo Trì (Maintenance Mode Driver)
    |--------------------------------------------------------------------------
    |
    | Các tùy chọn cấu hình này xác định driver được sử dụng để quản lý trạng thái
    | "chế độ bảo trì" của Laravel. Driver "cache" cho phép kiểm soát trạng thái
    | bảo trì trên nhiều máy chủ khác nhau.
    |
    | Các driver hỗ trợ: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
