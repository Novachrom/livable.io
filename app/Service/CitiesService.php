<?php

namespace App\Service;

use App\Client\NumbeoApiClient;
use App\Repository\CityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CitiesService
{
    /** @var NumbeoApiClient */
    private $apiClient;

    /** @var CityRepository */
    private $cityRepository;

    public function __construct(NumbeoApiClient $apiClient, CityRepository $cityRepository)
    {
        $this->apiClient = $apiClient;
        $this->cityRepository = $cityRepository;
    }

    public function fillNumbeoCities(): void
    {
        $cities = $this->apiClient->getCities();
        $this->cityRepository->saveNumbeoCities($cities);
    }

    public function getCititesPaginated(int $perPage = 100): LengthAwarePaginator
    {
        return $this->cityRepository->getCitiesPaginated($perPage);
    }
}
