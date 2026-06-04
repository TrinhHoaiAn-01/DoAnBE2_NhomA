<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Tệp này là nơi bạn có thể định nghĩa các lệnh CLI (Artisan commands) dựa
   trên Closure. Mỗi Closure sẽ được liên kết với một lệnh console cụ thể,
   cho phép tương tác nhanh chóng thông qua Terminal.
|
*/

// Định nghĩa lệnh 'inspire' hiển thị một câu châm ngôn truyền cảm hứng ngẫu nhiên
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Hiển thị một câu châm ngôn truyền cảm hứng');
