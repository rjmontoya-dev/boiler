<?php

namespace Boiler\Traits\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Boiler\Repositories\GenericRepository;

trait HasRepository
{
    /**
     * The repository instance.
     *
     * @var  GenericRepository
     */
    protected GenericRepository $repository;

    /**
     * Limit the number of items per page.
     *
     * @var integer
     */
    protected int $perPageLimit = 10;

    /**
     * Additional relationships for the repository.
     *
     * @var array
     */
    protected array $relationships = [];

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
     * Tab filters for the repository.
     *
     * @var mixed
     */
    protected mixed $tabFilter;

    /**
     * Additional sorters for the repository.
     *
     * @var array
     */
    protected array $additionalSorters = [];

    /**
     * Additional count conditions for the repository.
     *
     * @var array
     */
    protected array $additionalCounts = [];

    /**
     * The JSON resource class for formatting of the resource.
     *
     * @var string
     */
    protected string $jsonResource;

    /**
     * Default count conditions.
     *
     * @param bool $hasSoftDeletes
     *
     * @return array
     */
    protected function defaultCounts(bool $hasSoftDeletes = true): array
    {
        return [
            'activeCount' => $hasSoftDeletes ? 'deleted_at IS NULL' : '1',
            'archivedCount' => $hasSoftDeletes ? 'deleted_at IS NOT NULL' : '1',
        ];
    }

    /**
     * Set the relationships for the repository.
     *
     * @param array $relationships
     *
     * @return static
     */
    public function setRelationships(array $relationships): static
    {
        $this->relationships = $relationships;
        return $this;
    }

    /**
     * Set the additional filters for the repository.
     *
     * @param array $filters
     *
     * @return static
     */
    public function setAdditionalFilters(array $filters): static
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
    public function setAdditionalScopes(array $scopes): static
    {
        $this->additionalScopes = $scopes;
        return $this;
    }

    /**
     * Set the additional tab filters for the repository.
     *
     * @param mixed $filter
     *
     * @return static
     */
    public function setTabFilter($filter): static
    {
        $this->tabFilter = $filter;
        return $this;
    }

    /**
     * Set the additional sorters for the repository.
     *
     * @param array $sorters
     *
     * @return static
     */
    public function setAdditionalSorters(array $sorters): static
    {
        $this->additionalSorters = $sorters;
        return $this;
    }

    /**
     * Set the additional count cases for the repository.
     *
     * @param array $countCases
     *
     * @return static
     */
    public function setAdditionalCounts(array $countCases): static
    {
        $this->additionalCounts = $countCases;
        return $this;
    }

    /**
     * Set the JSON Resource class for the repository.
     *
     * @param array $jsonResource
     *
     * @return static
     */
    public function setJsonResource(string $jsonResource): static
    {
        $this->jsonResource = $jsonResource;
        return $this;
    }

    /**
     * Get the index data for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getRepositoryData(Request $request): array
    {
        $this->buildRepository();
        $query = $this->buildQuery($request);

        return [
            'items' => $this->getRepositoryPagination($query->clone(), $request),
            'counts' => $this->getRepositoryCount($query),
        ];
    }

    /**
     * Get the pagination data for the resource.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     *
     * @return LengthAwarePaginator|AnonymousResourceCollection
     */
    protected function getRepositoryPagination(Builder $query, Request $request): LengthAwarePaginator | AnonymousResourceCollection
    {
        /* Set default page limit */
        if (!$request->has('per_page')) {
            $request->merge([
                'per_page' => $this->perPageLimit,
            ]);
        }

        /* Paginate data */
        $query = $this->repository->filterByTab($query, $request);
        $items = $this->repository->paginate($query, $request);

        /* Format data if JsonResource is set */
        if (isset($this->jsonResource)) {
            $items = $this->jsonResource::collection($items);
        }

        return $items;
    }

    /**
     * Get the count data for the resource.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return array
     */
    protected function getRepositoryCount(Builder $query): array
    {
        $hasSoftDeletes = in_array(SoftDeletes::class, class_uses($query->getModel()));

        if ($hasSoftDeletes) {
            $query = $query->withTrashed();
        }

        $cases = array_merge($this->defaultCounts($hasSoftDeletes), $this->additionalCounts);

        $expressions = collect($cases)->map(
            fn($condition, string $alias) =>
            "COUNT(CASE WHEN {$condition} THEN 1 END) AS {$alias}"
        )
            ->implode(', ');

        $results = $query->selectRaw($expressions)->first();
        return $results ? $results->toArray() : [];
    }

    /**
     * Build the repository instance.
     *
     * @return void
     */
    protected function buildRepository(): void
    {
        $this->repository = $this->repository
            ->setFilters($this->additionalFilters)
            ->setSorters($this->additionalSorters)
            ->setScopes($this->additionalScopes);

        /* Set tab filter if declared */
        if (isset($this->tabFilter)) {
            $this->repository = $this->repository->setTabFilter($this->tabFilter);
        }
    }

    /**
     * Build the query for the repository.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery(Request $request): Builder
    {
        $query = $this->repository->newQuery();
        $query = $this->repository->withRelationships($query, $this->relationships);
        $query = $this->repository->filter($query, $request);

        return $this->repository->sort($query, $request);
    }
}
