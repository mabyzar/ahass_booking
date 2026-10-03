<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\BookingRepositoryInterface::class,
            \App\Repositories\Eloquent\BookingRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\ServicePackageRepositoryInterface::class,
            \App\Repositories\Eloquent\ServicePackageRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\ServicePartRepositoryInterface::class,
            \App\Repositories\Eloquent\ServicePartRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
