<?php

namespace Boiler\Traits\Repositories;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use ReflectionClass;

trait WithSort
{
    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected Model $model;

    /**
     * The sorters for the repository.
     *
     * @var array
     */
    protected array $sorters = [];

    /**
     * Set the sorters for the repository.
     *
     * @param array $sorters
     */
    public function setSorters(array $sorters): static
    {
        $this->sorters = $sorters;
        return $this;
    }

    /**
     * Sort the database records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sort(Builder $query, Request $request): Builder
    {
        /* Check the sort input has a value */
        if ($request->has('sort')) {

            $attribute = $request->sort;
            $order = $request->has('order') ? $request->order : 'asc';

            /* Get the method for sorting the given sort attribute */
            if ($sorterClass = $this->getSorterClass($attribute)) {
                $query = match (true) {

                    /* If callable, apply method directly */
                    $sorterClass instanceof Closure => $sorterClass($query, $attribute, $order),

                    /* If scope, then call it */
                    $this->isScope($sorterClass) => $query->$sorterClass($attribute, $order),

                    /* Or, just return the query */
                    default => $query
                };
            }
            /**
             * If attribute is not included in the sorters, then
             * check if it is a column in the database table.
             */
            elseif ($this->isColumn($attribute)) {
                $query = $query->orderBy($attribute, $order);
            }
        }

        /**
         * Default Sort.
         */
        return $query->orderByDesc('created_at');
    }

    /**
     * Get the sorter class for the given attribute.
     *
     * @param string $attribute
     *
     * @return mixed
     */
    protected function getSorterClass(string $attribute)
    {
        return array_key_exists($attribute, $this->sorters) ? $this->sorters[$attribute] : null;
    }

    /**
     * Determine if the sorter is a scope.
     *
     * @param mixed $sorterClass
     *
     * @return boolean
     */
    protected function isScope($sorterClass): bool
    {
        return is_string($sorterClass) ? $this->modelHasMethod('scope' . ucfirst($sorterClass)) : false;
    }

    /**
     * Determine if the sorter is a column.
     *
     * @param mixed $sorterClass
     *
     * @return boolean
     */
    protected function isColumn($sorterClass): bool
    {
        if (is_string($sorterClass) && isset($this->model)) {
            if ($this->modelHasMethod('hasColumn')) {
                return $this->model->hasColumn($sorterClass);
            }
            if ($table = $this->model->getTable()) {
                return Schema::hasColumn($table, $sorterClass);
            }
        }
        return false;
    }

    /**
     * Determine if the model has the given method.
     *
     * @param string $method
     *
     * @return boolean
     */
    protected function modelHasMethod(string $method): bool
    {
        $reflector = new ReflectionClass($this->model);
        return $reflector->hasMethod($method);
    }
}
