<?php

namespace Boiler\Filters;

use Illuminate\Database\Eloquent\Builder;
use Boiler\Contracts\FilterInterface;
use Boiler\Traits\CanFilterDate;

class DateFilter implements FilterInterface
{
    use CanFilterDate;

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
        return $this->dateRange($builder, $value);
    }
}
