<?php

namespace Boiler\Repositories;

use Boiler\Traits\Repositories\WithFilter;
use Boiler\Traits\Repositories\WithFilterTab;
use Boiler\Traits\Repositories\WithPagination;
use Boiler\Traits\Repositories\WithSort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GenericRepository
{
    use WithFilter;
    use WithFilterTab;
    use WithPagination;
    use WithSort;

    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(protected Model $model)
    {
    }

    /**
     * Instantiate a new query builder for the model's table.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Add relationships to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $relationships
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function withRelationships(Builder $query, array $relationships): Builder
    {
        return $query->with($relationships);
    }
}
