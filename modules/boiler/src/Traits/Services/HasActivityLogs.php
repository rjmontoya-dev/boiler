<?php

namespace Boiler\Traits\Services;

use App\Models\System\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Boiler\Http\Resources\ActivityIndexFormat;

trait HasActivityLogs
{
     /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected Model $model;

    /**
     * Limit the number of items per page.
     *
     * @var integer
     */
    protected int $perPageLimit = 10;

    /**
     * Get the activity logs.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getActivityLogs(Request $request): AnonymousResourceCollection
    {
        /* Set default page limit */
        if (!$request->has('per_page')) {
            $request->merge([
                'per_page' => $this->perPageLimit,
            ]);
        }

        $query = $this->getActivityQuery()->with(['causer', 'subject']);
        $query = $this->filterActivityLogs($query, $request);
        $query = $this->sortActivityLogs($query, $request);

        return ActivityIndexFormat::collection(
            $query->paginate($request->input('per_page'))->appends($request->query())
        );
    }

    /**
     * Get the activity count.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return integer
     */
    public function getActivityCount(Request $request): int
    {
        $query = $this->getActivityQuery();
        $query = $this->filterActivityLogs($query, $request);

        return $query->count();
    }

    /**
     * Get the activity query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getActivityQuery(): Builder
    {
        return Activity::when(isset($this->model), fn($q) => $q->whereHasMorph('subject', $this->model::class))->newQuery();
    }

    /**
     * Get the specific activity query by subject type and subject id.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getSpecificActivity(Model $model): Builder
    {
        return Activity::forSubject($model)->newQuery();
    }

    /**
     * Filter the activity logs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filterActivityLogs(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->has('event'), fn(Builder $query) => $query->where('event', $request->event));
            // ->when($request->has('query'), fn(Builder $query) => (new SearchFilter())->apply($query, $request->input('query')));
    }

    /**
     * Sort the activity logs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function sortActivityLogs(Builder $query, Request $request): Builder
    {
        if ($attribute = $request->sort) {
            $order = $request->order ?? 'ASC';
            $query = match ($attribute) {
                'subject' => $query->sortBySubject(get_class($this->model), $order),
                default => $query->orderBy($attribute, $order),
            };
        }
        return $query->orderBy('created_at', 'DESC');
    }
}
