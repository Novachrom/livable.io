<?php

namespace App\Decorator;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class QueryDecoratorCollection
{
    /** @var Collection<QueryDecorator> */
    private $decorators;

    public function __construct(QueryDecorator ...$decorators)
    {
        $this->decorators = collect();
        foreach ($decorators as $decorator) {
            $this->decorators->push($decorator);
        }
    }

    public function decorate(Builder $query, array $params): Builder
    {
        /** @var QueryDecorator $decorator */
        foreach ($this->decorators as $decorator) {
            $query = $decorator->decorate($query, $params);
        }

        return $query;
    }
}
