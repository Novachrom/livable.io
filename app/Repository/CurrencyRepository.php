<?php

namespace App\Repository;

use App\Currency;

class CurrencyRepository
{
    public function storeCurrencyRate(string $currencyCode, float $rate)
    {
        $currency = Currency::query()->firstOrNew(['code' => $currencyCode]);
        $currency->usd_rate = $rate;

        return $currency->save();
    }
}
