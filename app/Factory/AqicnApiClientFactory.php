<?php

namespace App\Factory;

use App\Client\AqicnApiClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Config\Repository;

class AqicnApiClientFactory
{
    /** @var Repository */
    private $config;

    public function __construct(\Illuminate\Config\Repository $config)
    {
        $this->config = $config;
    }

    public function create(): AqicnApiClient
    {
        $config = [
            'base_uri' => $this->config->get('aqicn.base_url')
        ];
        $guzzleAdapter = GuzzleAdapter::createWithConfig($config);

        return new AqicnApiClient($guzzleAdapter, new GuzzleMessageFactory, $this->config->get('aqicn.token'));
    }
}
