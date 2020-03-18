<?php

namespace App\Service;

use App\City;
use App\Repository\CurrencyRepository;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Session\Session;

class CostOfLivingCalculator
{
    private const MODE_LOCAL = 'local';
    private const MODE_DEFAULT = 'default';

    /** @var Repository */
    private $config;

    /** @var Session */
    private $session;

    public function __construct(Repository $config, Session $session)
    {
        $this->config = $config;
        $this->session = $session;
    }

    public function calculate(City $city): string
    {
        $mode = $this->getCurrnecyMode();
        $coef = $this->getCostOfLivingCoef();

        if($mode == self::MODE_DEFAULT) {
            return ($city->cost_of_living * $coef) . ' USD';
        } else {
            $currency = $city->country->currency;
            if(empty($currency) || $currency->code === 'USD') {
                return ($city->cost_of_living * $coef) . ' USD';
            }

            $value = $city->cost_of_living * $currency->usd_rate * $coef;

            return $value . " {$currency->code}";
        }
    }

    private function getCurrnecyMode(): string
    {
        $mode = $this->session->get('currency_mode');
        if(empty($mode) || !in_array($mode, [self::MODE_DEFAULT, self::MODE_LOCAL])) {

            return self::MODE_LOCAL;
        }

        return $mode;
    }

    private function getCostOfLivingCoef(): float
    {
        return $this->config->get('app.cost_of_living_coef');
    }
}
