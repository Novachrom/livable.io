<?php

namespace App\Client;

use App\DTO\Aqicn\FeedResponse;
use App\Exceptions\AqicnApiException;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Psr\Http\Client\ClientExceptionInterface;

class AqicnApiClient
{
    /** @var HttpClient */
    private $httpClient;

    /** @var RequestFactory  */
    private $requestFactory;

    /** @var string */
    private $token;

    public function __construct(HttpClient $httpClient, RequestFactory $requestFactory, string $token)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->token = $token;
    }

    public function sendRequest(string $path, string $method = 'get', array $headers = []): array
    {
        try {
            $queryParams = ['token' => $this->token];
            $uri = $path . '/?' . http_build_query($queryParams);

            $request = $this->requestFactory->createRequest($method, $uri, $headers);
            $response = $this->httpClient->sendRequest($request);

            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientExceptionInterface $e) {
            //todo: logging

            throw $e;
        }
    }

    public function getDataForCity(string $city): FeedResponse
    {
        $response = $this->sendRequest($city);
        if(empty($response['status']) || $response['status'] === 'error' || !is_array($response['data'])) {
            throw new AqicnApiException("Send request to aqicn api failed: " . $response['data'] ?? '');
        }

        return FeedResponse::fromArray($response['data']);
    }
}
