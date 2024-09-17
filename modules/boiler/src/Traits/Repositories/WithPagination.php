<?php

namespace Boiler\Traits\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait WithPagination
{
    /**
     * The number of records to show per page.
     *
     * @var integer
     */
    protected int $limit = 10;

    /**
     * Paginate the database records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(Builder $query, Request $request): LengthAwarePaginator
    {
        return $query
            ->paginate($request->input('per_page', $this->limit))
            ->appends($request->query());
    }
}
