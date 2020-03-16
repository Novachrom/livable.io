<?php

namespace App\Repository;

use App\City;
use App\CityCalculation;
use App\CustomCalculation;
use App\DTO\CustomCalculation\Formula;

class CustomCalculationRepository
{
    public function getAllCalculations()
    {
        return CustomCalculation::query()
            ->get();
    }

    public function create(Formula $formula): CustomCalculation
    {
        $model = new CustomCalculation();
        $model->var_name = $formula->getVarName();
        $model->formula = $formula->getFormula();
        $model->description = $formula->getDescription();
        $model->save();

        return $model;
    }

    public function saveForCity(City $city, CustomCalculation $calculation, $value): void
    {
        $model = new CityCalculation();
        $model->city_id = $city->id;
        $model->calculation_id = $calculation->id;
        $model->value = $value;
        $model->save();
    }
}
