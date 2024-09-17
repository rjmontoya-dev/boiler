<?php

namespace Boiler\Filters;

use Illuminate\Database\Eloquent\Builder;
use Boiler\Contracts\FilterInterface;

class TabFilter implements FilterInterface
{
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
        return $builder->when($value == 'archived', fn($query) => $query->onlyTrashed());
    }
}
