<?php


namespace App\Factory;


use App\Client\ExchangeRateApiClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Config\Repository;

class ExchangeRateApiClientFactory
{
    /** @var Repository */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    public function create(): ExchangeRateApiClient
    {
        $config = [
            'base_uri' => $this->config->get('exchange_rate.base_url')
        ];
        $guzzleAdapter = GuzzleAdapter::createWithConfig($config);

        return new ExchangeRateApiClient($guzzleAdapter, new GuzzleMessageFactory());
    }
}
