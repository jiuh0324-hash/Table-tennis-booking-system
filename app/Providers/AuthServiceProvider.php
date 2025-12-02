<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * 应用程序的策略映射
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Booking' => 'App\Policies\BookingPolicy',
        'App\Models\TableTennisTable' => 'App\Policies\TableTennisTablePolicy',
    ];

    /**
     * 注册任何身份验证/授权服务
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}