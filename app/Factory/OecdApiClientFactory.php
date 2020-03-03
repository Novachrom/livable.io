<?php


namespace App\Factory;


use App\Client\OecdApiClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Config\Repository;

class OecdApiClientFactory
{
    /** @var Repository */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    public function create(): OecdApiClient
    {
        $config = [
            'base_uri' => $this->config->get('oecd.base_url')
        ];
        $guzzleAdapter = GuzzleAdapter::createWithConfig($config);

        return new OecdApiClient($guzzleAdapter, new GuzzleMessageFactory());
    }
}
