<?php

namespace App\Providers;

use App\Client\AqicnApiClient;
use App\Client\ExchangeRateApiClient;
use App\Client\NumbeoApiClient;
use App\Client\OecdApiClient;
use App\Decorator\CityCostOfLivingFilter;
use App\Decorator\CitySortingDecorator;
use App\Decorator\QueryDecoratorCollection;
use App\Factory\AqicnApiClientFactory;
use App\Factory\ExchangeRateApiClientFactory;
use App\Factory\NumbeoApiClientFactory;
use App\Factory\OecdApiClientFactory;
use App\Repository\CityRepository;
use App\Service\CostOfLivingCalculator;
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

        $this->app->singleton(OecdApiClient::class, function () {
            /** @var OecdApiClientFactory $factory */
            $factory = $this->app->get(OecdApiClientFactory::class);

            return $factory->create();
        });

        $this->app->singleton(ExchangeRateApiClient::class, function () {
            /** @var ExchangeRateApiClientFactory $factory */
            $factory = $this->app->get(ExchangeRateApiClientFactory::class);

            return $factory->create();
        });

        $this->app->when(CityRepository::class)
            ->needs(QueryDecoratorCollection::class)
            ->give(function () {
                return new QueryDecoratorCollection(
                    new CitySortingDecorator(),
                    new CityCostOfLivingFilter()
                );
            });

//        $instance =
//
        $this->app->singleton(CostOfLivingCalculator::class);
    }
}
