<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;



class AppServiceProvider extends ServiceProvider
{


    /**
     * ブートストラップの使用
     *
     * @return void
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
