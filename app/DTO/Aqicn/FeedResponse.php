<?php


namespace App\DTO\Aqicn;


class FeedResponse
{
    /** @var string */
    private $city;

    /** @var float */
    private $aqi;

    public function __construct(string $city, float $aqi)
    {
        $this->city = $city;
        $this->aqi = $aqi;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return float
     */
    public function getAqi(): float
    {
        return $this->aqi;
    }

    public static function fromArray(array $data): self
    {
        $aqi = (float)$data['aqi'];
        $name = $data['city']['name'];

        return new self($name, $aqi);
    }
}
