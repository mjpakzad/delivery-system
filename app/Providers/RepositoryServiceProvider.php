<?php

namespace App\Providers;

use App\Repositories\BusinessRepository;
use App\Repositories\Contracts\BusinessRepositoryInterface;
use App\Repositories\Contracts\CourierRepositoryInterface;
use App\Repositories\Contracts\ParcelRepositoryInterface;
use App\Repositories\Contracts\TokenRepositoryInterface;
use App\Repositories\CourierRepository;
use App\Repositories\ParcelRepository;
use App\Repositories\TokenRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ParcelRepositoryInterface::class, ParcelRepository::class);
        $this->app->bind(TokenRepositoryInterface::class, TokenRepository::class);
        $this->app->bind(BusinessRepositoryInterface::class, BusinessRepository::class);
        $this->app->bind(CourierRepositoryInterface::class, CourierRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
