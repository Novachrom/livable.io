<?php

namespace App\Client;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Psr\Http\Client\ClientExceptionInterface;

class OecdApiClient
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

    public function getBliData()
    {
        $response = $this->sendRequest('BLI');
        $items = [];
        foreach ($response['dataSets'][0]['observations'] as $key => $values) {
            $item = [];
            foreach (explode(':', $key) as $index => $dimension) {
                $name = $response['structure']['dimensions']['observation'][$index]['name'];
                $item[$name] = $response['structure']['dimensions']['observation'][$index]['values'][$dimension]['name'];
                $item['values'] = $values;
            }
            $items[] = $item;
        }

        $res = [];
        foreach ($items as $item) {
            if(!isset($res[$item['Country']])) {
                $res[$item['Country']] = [];
            }
            $key = $item['Indicator'] . ' ' . $item['Inequality'];
            $res[$item['Country']][$key] = $item['values'][0];
        }
        return $res;
    }
}
