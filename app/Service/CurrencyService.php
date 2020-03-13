<?php

namespace App\Service;

use App\Client\ExchangeRateApiClient;
use App\Repository\CurrencyRepository;

class CurrencyService
{
    /** @var ExchangeRateApiClient */
    private $exchangeRateApiClient;

    /** @var CurrencyRepository */
    private $currencyRepository;

    public function __construct(ExchangeRateApiClient $exchangeRateApiClient, CurrencyRepository $currencyRepository, CurrencyRepository $currencyRepository1)
    {
        $this->exchangeRateApiClient = $exchangeRateApiClient;
        $this->currencyRepository = $currencyRepository1;
    }

    public function fillCurrencyRates(): void
    {
        $rates = $this->exchangeRateApiClient->getRates();
        foreach ($rates as $currency => $rate) {
            $this->currencyRepository->storeCurrencyRate($currency, (float)$rate);
        }
    }
}
