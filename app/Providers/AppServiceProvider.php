<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // FIX GLOBAL tất cả string - giảm xuống 125 để hỗ trợ composite key (125*2*4 = 1000 bytes)
        Schema::defaultStringLength(125);
    }
}