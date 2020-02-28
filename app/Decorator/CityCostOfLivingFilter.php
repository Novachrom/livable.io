<?php

namespace App\Decorator;

use Illuminate\Database\Eloquent\Builder;

class CityCostOfLivingFilter implements QueryDecorator
{
    private const PARAM_FROM = 'cost_of_living_from';
    private const PARAM_TO = 'cost_of_living_to';

    public function decorate(Builder $query, array $params): Builder
    {
        if(empty($params[self::PARAM_FROM]) || empty($params[self::PARAM_TO])) {
            return $query;
        }

        $from = $params[self::PARAM_FROM];
        $to = $params[self::PARAM_TO];

        return $query
            ->whereBetween('cost_of_living', [$from, $to]);
    }
}
