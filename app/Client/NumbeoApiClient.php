<?php

namespace App\Client;

use App\DTO\Numbeo\City;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Psr\Http\Client\ClientExceptionInterface;
use Illuminate\Contracts\Config\Repository;

class NumbeoApiClient
{
    /** @var HttpClient */
    private $httpClient;

    /** @var RequestFactory  */
    private $requestFactory;

    /** @var Repository */
    private $config;

    public function __construct(HttpClient $httpClient, RequestFactory $requestFactory, Repository $config)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->config = $config;
    }

    public function sendRequest(string $path, string $method, array $queryParams = [], array $headers = []): array
    {
        try {
            $basePath = $this->config->get('numbeo.base_url');
            $apiKey = $this->config->get('numbeo.api_key');
            $queryParams['api_key'] = $apiKey;
            $uri = $basePath . $path . '?' . http_build_query($queryParams);

            $request = $this->requestFactory->createRequest($method, $uri, $headers);
            $response = $this->httpClient->sendRequest($request);

            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientExceptionInterface $e) {
            //todo: logging

            throw $e;
        }
    }

    /**
     * @return
     */
    public function getCities(): array
    {
        $citiesResponse = $this->sendRequest('cities', 'get');

        $res = [];
        foreach($citiesResponse['cities'] as $index => $city) {
            echo $index.PHP_EOL;
            $indices = $this->sendRequest('indices', 'get', ['city_id' => $city['city_id']]);

            $res[] = new City(
                $city['city_id'],
                $city["city"],
                $city["country"],
                (float)($indices["contributors_cost_of_living"] ?? 0),
                (float)($indices["health_care_index"] ?? 0),
                (float)($indices["crime_index"] ?? 0),
                (float)($indices["traffic_time_index"] ?? 0),
                (float)($indices["quality_of_life_index"] ?? 0),
                (float)($indices["restaurant_price_index"] ?? 0)
            );
        }

        return $res;
    }
}
