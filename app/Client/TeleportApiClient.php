<?php

namespace App\Client;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Psr\Http\Client\ClientExceptionInterface;

class TeleportApiClient
{
    /** @var HttpClient */
    private $httpClient;

    /** @var RequestFactory  */
    private $requestFactory;

    public function __construct(HttpClient $httpClient, RequestFactory $requestFactory)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
    }

    public function sendRequest(string $path, string $method = 'get')
    {
        try {

            $request = $this->requestFactory->createRequest($method, $path);
            $response = $this->httpClient->sendRequest($request);

            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientExceptionInterface $e) {
            //todo: logging

            throw $e;
        }
    }

    public function getLocations(string $lat, string $lng): array
    {
        $path = sprintf('locations/%s,%s', $lat, $lng);
        $response = $this->sendRequest($path);

        return $response;
    }

}
