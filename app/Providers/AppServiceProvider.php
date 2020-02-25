<?php

namespace App\Providers;

use App\Client\NumbeoApiClient;
use App\Factory\NumbeoApiClientFactory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->app->singleton(NumbeoApiClient::class, function () {
            /** @var NumbeoApiClientFactory $factory */
            $factory = $this->app->get(NumbeoApiClientFactory::class);

            return $factory->create();
        });
    }
}
