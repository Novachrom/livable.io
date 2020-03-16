<?php

namespace App\Service;

use App\City;
use App\DTO\CustomCalculation\Formula;
use App\Repository\CityRepository;
use App\Repository\CustomCalculationRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CustomCalculationsService
{
    /** @var CityRepository */
    private $cityRepository;

    /** @var CustomCalculationRepository */
    private $customCalculationRepository;

    public function __construct(CityRepository $cityRepository, CustomCalculationRepository $customCalculationRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->customCalculationRepository = $customCalculationRepository;
    }

    public function fetch(): Collection
    {
        return $this->customCalculationRepository->getAllCalculations();
    }

    public function create(Formula $dto)
    {
        DB::beginTransaction();
        try {
            $calculation = $this->customCalculationRepository->create($dto);
            foreach ($this->cityRepository->getAllCities() as $city) {
                $value = $this->evaluateFormulaForCity($city, $dto);
                $this->customCalculationRepository->saveForCity($city, $calculation, $value);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function evaluateFormulaForCity(City $city, Formula $dto)
    {
        $evaluator = new \App\Matex\Evaluator();
        $evaluator->variables = $city->getAvailableVariables();

        return $evaluator->execute($dto->getFormula());
    }

    public function getAvailableVariables(): array
    {
        return $this->cityRepository->getAvavilableVariables();
    }
}
