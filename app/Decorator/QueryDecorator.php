<?php

namespace App\Decorator;

use Illuminate\Database\Eloquent\Builder;

interface QueryDecorator
{
    public function decorate(Builder $query, array $params): Builder;
}
