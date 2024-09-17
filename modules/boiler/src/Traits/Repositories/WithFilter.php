<?php

namespace Boiler\Traits\Repositories;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Boiler\Filters\DateFilter;
use Boiler\Filters\SearchFilter;

trait WithFilter
{
    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected Model $model;

    /**
     * Additional filters for the repository.
     *
     * @var array
     */
    protected array $additionalFilters = [];

    /**
     * Additional scopes for the repository.
     *
     * @var array
     */
    protected array $additionalScopes = [];

    /**
     * Get the default filters for the repository.
     *
     * @return array
     */
    protected function defaultFilters(): array
    {
        return [
            'query' => SearchFilter::class,
            'search' => SearchFilter::class,
            'date' => DateFilter::class,
        ];
    }

    /**
     * Set the additional filters for the repository.
     *
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters): static
    {
        $this->additionalFilters = $filters;
        return $this;
    }

    /**
     * Set the additional scopes for the repository.
     *
     * @param array<Closure|string> $scopes
     *
     * @return static
     */
    public function setScopes(array $scopes): static
    {
        $this->additionalScopes = $scopes;
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
    public function filter(Builder $query, Request $request): Builder
    {
        /* Merge/override defaultFilters with additionalFilters */
        $filters = array_merge($this->defaultFilters(), $this->additionalFilters);

        foreach ($filters as $filterKey => $filterClass) {
            if ($request->has($filterKey)) {

                $value = $request->input($filterKey);
                $query = match (true) {

                    /* If filterClass is a callable, apply method directly */
                    $filterClass instanceof Closure => $filterClass($query, $value),

                    /* If filterClass is a class, instantiate and apply */
                    class_exists($filterClass) => (new $filterClass())->apply($query, $value),

                    /**
                     * If filterClass is a simple value,
                     * check if the filterKey exists as a column in the model's table
                     * and apply the filter if it does
                     */
                    default => isset($this->model) && $this->model->hasColumn($filterKey)
                    ? $query->where($filterKey, $value)
                    : $query,
                };
            }
        }

        foreach ($this->additionalScopes as $scope) {
            $query = match (true) {
                $scope instanceof Closure => $scope($query),
                default => $query->scopes($scope),
            };
        }

        return $query;
    }
}
