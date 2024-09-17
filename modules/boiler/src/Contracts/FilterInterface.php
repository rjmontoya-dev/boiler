<?php

namespace Boiler\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{
    /**
     * Apply the filter to the given query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, $value): Builder;
}
