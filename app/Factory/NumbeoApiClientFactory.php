<?php

namespace App\Factory;

use App\Client\NumbeoApiClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Illuminate\Config\Repository;

class NumbeoApiClientFactory
{
    /** @var Repository */
    private $config;

    public function __construct(\Illuminate\Config\Repository $config)
    {
        $this->config = $config;
    }

    public function create(): NumbeoApiClient
    {
        $guzzleAdapter = GuzzleAdapter::createWithConfig([]);

        return new NumbeoApiClient($guzzleAdapter, new GuzzleMessageFactory, $this->config);
    }
}
