<?php

namespace Boiler\Traits\Repositories;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Boiler\Filters\TabFilter;

trait WithFilterTab
{
    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected Model $model;

    /**
     * Tab filter for the repository.
     *
     * @var mixed
     */
    protected $tabFilter = TabFilter::class;

    /**
     * Set the additional filters for the repository.
     *
     * @param mixed $filter
     *
     * @return $this
     */
    public function setTabFilter($filter): static
    {
        $this->tabFilter = $filter;
        return $this;
    }

    /**
     * Prepare the query with the given filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterByTab(Builder $query, Request $request): Builder
    {
        $filterKey = 'tab';
        $filterClass = $this->tabFilter;

        if($request->has($filterKey)) {

            $value = $request->input($filterKey);

            $query = match(true) {

                /* If filterClass is a callable, apply method directly */
                $filterClass instanceof Closure => $filterClass($query, $value),

                /* If filterClass is a class, instantiate and apply */
                class_exists($filterClass) => (new $filterClass())->apply($query, $value),
                default => $query,
            };
        }

        return $query;
    }
}
