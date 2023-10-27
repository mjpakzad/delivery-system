<?php

namespace App\Providers;

use App\Services\BusinessService;
use App\Services\Contracts\BusinessServiceInterface;
use App\Services\Contracts\CourierServiceInterface;
use App\Services\Contracts\ParcelServiceInterface;
use App\Services\Contracts\TokenServiceInterface;
use App\Services\CourierService;
use App\Services\ParcelService;
use App\Services\TokenService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ParcelServiceInterface::class, ParcelService::class);
        $this->app->bind(TokenServiceInterface::class, TokenService::class);
        $this->app->bind(BusinessServiceInterface::class, BusinessService::class);
        $this->app->bind(CourierServiceInterface::class, CourierService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
