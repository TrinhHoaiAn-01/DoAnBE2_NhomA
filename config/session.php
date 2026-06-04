<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Trình Quản Lý Session Mặc Định (Default Session Driver)
    |--------------------------------------------------------------------------
    |
    | Tùy chọn này xác định driver quản lý session mặc định sẽ được sử dụng cho
    | các yêu cầu (requests) gửi đến. Laravel hỗ trợ nhiều tùy chọn lưu trữ
    | khác nhau để duy trì dữ liệu session. Sử dụng database là một lựa chọn tốt.
    |
    | Hỗ trợ: "file", "cookie", "database", "apc",
    |         "memcached", "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Thời Gian Tồn Tại Của Session (Session Lifetime)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể chỉ định số phút mà session được phép không hoạt động
    | (idle) trước khi hết hạn. Nếu muốn session hết hạn ngay lập tức khi đóng
    | trình duyệt, hãy bật tùy chọn cấu hình `expire_on_close`.
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    // Hết hạn session khi đóng trình duyệt
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Mã Hóa Dữ Liệu Session (Session Encryption)
    |--------------------------------------------------------------------------
    |
    | Tùy chọn này cho phép bạn dễ dàng chỉ định rằng tất cả dữ liệu session
    | nên được mã hóa trước khi lưu trữ. Tất cả các thao tác mã hóa được thực hiện
    | tự động bởi Laravel và bạn có thể sử dụng session như bình thường.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Vị Trí Lưu Tệp Session (Session File Location)
    |--------------------------------------------------------------------------
    |
    | Khi sử dụng driver session là "file", các tệp session sẽ được lưu trên đĩa.
    | Vị trí lưu trữ mặc định được định nghĩa ở đây, tuy nhiên bạn có thể tự do
    | thay đổi sang thư mục khác nếu muốn.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Kết Nối Cơ Sở Dữ Liệu Cho Session (Session Database Connection)
    |--------------------------------------------------------------------------
    |
    | Khi sử dụng driver session là "database" hoặc "redis", bạn có thể chỉ định
    | kết nối cơ sở dữ liệu cụ thể nào sẽ được sử dụng để quản lý các session này.
    | Kết nối này phải tương ứng với một kết nối được cấu hình trong database.php.
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Bảng Lưu Session Trong Cơ Sở Dữ Liệu (Session Database Table)
    |--------------------------------------------------------------------------
    |
    | Khi sử dụng driver session là "database", bạn cần chỉ định bảng được sử dụng
    | để lưu trữ các session. Một bảng mặc định hợp lý đã được định nghĩa sẵn.
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Bộ Lưu Trữ Cache Cho Session (Session Cache Store)
    |--------------------------------------------------------------------------
    |
    | Khi sử dụng các driver session dựa trên Cache, bạn có thể chỉ định bộ lưu trữ
    | cache (cache store) cụ thể để lưu dữ liệu giữa các request.
    |
    | Ảnh hưởng đến: "apc", "dynamodb", "memcached", "redis"
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Tỷ Lệ Dọn Dẹp Session Hết Hạn (Session Sweeping Lottery)
    |--------------------------------------------------------------------------
    |
    | Một số driver session phải dọn dẹp thư mục lưu trữ thủ công để loại bỏ các
    | session đã hết hạn. Đây là tỷ lệ phần trăm cơ hội việc này diễn ra trong
    | một request. Mặc định là 2% (2 trên 100).
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Tên Cookie Session (Session Cookie Name)
    |--------------------------------------------------------------------------
    |
    | Tại đây bạn có thể thay đổi tên của cookie session được tạo bởi framework.
    | Thông thường bạn không cần phải thay đổi giá trị này vì việc đó không mang
    | lại cải tiến bảo mật đáng kể nào.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Đường Dẫn Cookie Session (Session Cookie Path)
    |--------------------------------------------------------------------------
    |
    | Đường dẫn cookie session xác định các đường dẫn (URL) nào mà cookie này
    | sẽ khả dụng. Mặc định là đường dẫn gốc "/" của ứng dụng.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Tên Miền Cookie Session (Session Cookie Domain)
    |--------------------------------------------------------------------------
    |
    | Giá trị này xác định tên miền (domain) và tên miền con (subdomain) mà
    | cookie session có hiệu lực. Mặc định cookie sẽ khả dụng cho tất cả.
    |
    */

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Chỉ Gửi Cookie Qua HTTPS (HTTPS Only Cookies)
    |--------------------------------------------------------------------------
    |
    | Bằng cách đặt tùy chọn này thành true, cookie session sẽ chỉ được trình duyệt
    | gửi ngược lại máy chủ nếu kết nối hiện tại là HTTPS bảo mật.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE'),

    /*
    |--------------------------------------------------------------------------
    | Chỉ Truy Cập Qua Giao Thức HTTP (HTTP Access Only)
    |--------------------------------------------------------------------------
    |
    | Đặt giá trị này thành true sẽ ngăn chặn JavaScript truy cập vào giá trị của
    | cookie, giúp giảm thiểu rủi ro từ các cuộc tấn công XSS (Cross-Site Scripting).
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Cấu Hình Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | Tùy chọn này xác định cách cookie hoạt động khi xảy ra các yêu cầu liên trang
    | (cross-site requests), giúp giảm thiểu các cuộc tấn công CSRF.
    | Mặc định được đặt là "lax" để cho phép chia sẻ an toàn.
    |
    | Hỗ trợ: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Cấu Hình Partitioned Cookies
    |--------------------------------------------------------------------------
    |
    | Đặt giá trị này thành true sẽ liên kết cookie với trang web cấp cao nhất
    | trong ngữ cảnh liên trang (cross-site context). Cookie phân vùng được chấp nhận
    | khi được gắn cờ "secure" và thuộc tính Same-Site được đặt thành "none".
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
