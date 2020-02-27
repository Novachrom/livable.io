<?php


namespace App\DTO\Numbeo;


class City
{
    /** @var int */
    private $cityId;

    /** @var string */
    private $name;

    /** @var string */
    private $countryName;

    /** @var float */
    private $costOfLiving;

    /** @var float */
    private $healthCareIndex;

    /** @var float */
    private $crimeIndex;

    /** @var float */
    private $trafficTimeIndex;

    /** @var float */
    private $qualityOfLifeIndex;

    /** @var float */
    private $restaurantPriceIndex;

    /** @var float */
    private $latitude;

    /** @var float */
    private $longitude;

    public function __construct(int $cityId, string $name, string $countryName, float $costOfLiving, float $healthCareIndex, float $crimeIndex, float $trafficTimeIndex, float $qualityOfLifeIndex, float $restaurantPriceIndex, float $latitude, float $longitude)
    {
        $this->cityId = $cityId;
        $this->name = $name;
        $this->countryName = $countryName;
        $this->costOfLiving = $costOfLiving;
        $this->healthCareIndex = $healthCareIndex;
        $this->crimeIndex = $crimeIndex;
        $this->trafficTimeIndex = $trafficTimeIndex;
        $this->qualityOfLifeIndex = $qualityOfLifeIndex;
        $this->restaurantPriceIndex = $restaurantPriceIndex;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return int
     */
    public function getCityId(): int
    {
        return $this->cityId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCountryName(): string
    {
        return $this->countryName;
    }

    /**
     * @return float
     */
    public function getCostOfLiving(): float
    {
        return $this->costOfLiving;
    }

    /**
     * @return float
     */
    public function getHealthCareIndex(): float
    {
        return $this->healthCareIndex;
    }

    /**
     * @return float
     */
    public function getCrimeIndex(): float
    {
        return $this->crimeIndex;
    }

    /**
     * @return float
     */
    public function getTrafficTimeIndex(): float
    {
        return $this->trafficTimeIndex;
    }

    /**
     * @return float
     */
    public function getQualityOfLifeIndex(): float
    {
        return $this->qualityOfLifeIndex;
    }

    /**
     * @return float
     */
    public function getRestaurantPriceIndex(): float
    {
        return $this->restaurantPriceIndex;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
