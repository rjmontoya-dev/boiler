<?php

namespace Boiler\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait CanSearch
{
    /**
     * Apply the search criteria to the query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string|null $search
     * @param array $within
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applySearchToQueryBuilder(Builder $builder, ?string $search = null, array $within = []): Builder
    {
        if ($search) {
            $searchResult = $this->performSearch($builder, $search, $within);
            $builder = $this->filterBuilderWithSearchResults($builder, $searchResult->toArray());
        }

        return $builder;
    }

    /**
     * Perform the actual search operation.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $search
     * @param array $fields
     *
     * @return \Illuminate\Support\Collection
     */
    protected function performSearch(Builder $builder, string $search, array $fields = []): Collection
    {
        return $builder->getModel()
            ->search($search, function ($meilisearch, $query, $options) use ($fields) {
                $this->configureMeilisearch($meilisearch);
                $options['attributesToHighlight'] = ['*'];
                $options['hitsPerPage'] = 1_000_000;

                // will override toSearchableArray
                if ($fields) {
                    $options['attributesToSearchOn'] = $fields;
                }

                return $meilisearch->search($query, $options);
            })
            ->keys();
    }

    /**
     * Configure the MeiliSearch instance.
     *
     * @param mixed $meilisearch the MeiliSearch instance
     */
    protected function configureMeilisearch(mixed $meilisearch): void
    {
        $meilisearch->updateRankingRules([
            'exactness',
            'words',
            'proximity',
            'attribute',
            'sort',
            'typo',
        ]);

        $meilisearch->updateTypoTolerance([
            'enabled' => false,
            'minWordSizeForTypos' => [
                'oneTypo' => 2,
                'twoTypos' => 5,
            ],
        ]);
    }

    /**
     * Filter the query builder with search results.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterBuilderWithSearchResults(Builder $builder, array $ids): Builder
    {
        return $builder->whereIn("{$builder->getModel()->getTable()}.id", $ids);
    }
}
