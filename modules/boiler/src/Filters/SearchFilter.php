<?php

namespace Boiler\Filters;

use Illuminate\Database\Eloquent\Builder;
use Boiler\Contracts\FilterInterface;
use Boiler\Traits\CanSearch;

class SearchFilter implements FilterInterface
{
    use CanSearch;

    /**
     * Apply the filter to the given query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, $value): Builder
    {
        return $this->applySearchToQueryBuilder($builder, $value);
    }
}
