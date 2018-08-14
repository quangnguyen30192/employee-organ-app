<?php

namespace App\Providers;

use App\DataProviders\EmployeeDataProvider;
use App\DataProviders\EmployeeJsonDataProvider;
use App\Services\EmployeeTreeService;
use App\Services\EmployeeTreeServiceImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EmployeeDataProvider::class, EmployeeJsonDataProvider::class);
        $this->app->bind(EmployeeTreeService::class, EmployeeTreeServiceImpl::class);
    }
}
