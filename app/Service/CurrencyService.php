<?php

namespace App\Service;

use App\Client\ExchangeRateApiClient;
use App\Repository\CurrencyRepository;
use Illuminate\Contracts\Session\Session;

class CurrencyService
{
    /** @var ExchangeRateApiClient */
    private $exchangeRateApiClient;

    /** @var CurrencyRepository */
    private $currencyRepository;

    /** @var Session */
    private $session;

    public function __construct(ExchangeRateApiClient $exchangeRateApiClient, CurrencyRepository $currencyRepository, CurrencyRepository $currencyRepository1, \Illuminate\Contracts\Session\Session $session)
    {
        $this->exchangeRateApiClient = $exchangeRateApiClient;
        $this->currencyRepository = $currencyRepository1;
        $this->session = $session;
    }

    public function fillCurrencyRates(): void
    {
        $rates = $this->exchangeRateApiClient->getRates();
        foreach ($rates as $currency => $rate) {
            $this->currencyRepository->storeCurrencyRate($currency, (float)$rate);
        }
    }

    public function setCurrencyMode(string $mode): void
    {
        $this->session->put('currency_mode', $mode);
    }
}
