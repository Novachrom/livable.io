<?php


namespace App\Factory;


use App\Client\OecdApiClient;
use App\Client\TeleportApiClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Config\Repository;

class TeleportApiClientFactory
{
    /** @var Repository */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    public function create(): TeleportApiClient
    {
        $config = [
            'base_uri' => $this->config->get('teleport.base_url')
        ];
        $guzzleAdapter = GuzzleAdapter::createWithConfig($config);

        return new TeleportApiClient($guzzleAdapter, new GuzzleMessageFactory());
    }
}
