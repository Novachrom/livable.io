<?php

namespace App\Providers;

use App\Client\AqicnApiClient;
use App\Client\NumbeoApiClient;
use App\Decorator\CitySortingDecorator;
use App\Decorator\QueryDecoratorCollection;
use App\Factory\AqicnApiClientFactory;
use App\Factory\NumbeoApiClientFactory;
use App\Repository\CityRepository;
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

        $this->app->singleton(AqicnApiClient::class, function () {
            /** @var AqicnApiClientFactory $factory */
            $factory = $this->app->get(AqicnApiClientFactory::class);

            return $factory->create();
        });

        $this->app->when(CityRepository::class)
            ->needs(QueryDecoratorCollection::class)
            ->give(function () {
                return new QueryDecoratorCollection(
                    new CitySortingDecorator()
                );
            });
    }
}
