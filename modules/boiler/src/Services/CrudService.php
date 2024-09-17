<?php

namespace Boiler\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Boiler\Repositories\GenericRepository;
use Boiler\Traits\Services\HasActivityLogs;
use Boiler\Traits\Repositories\WithFilter;
use Boiler\Traits\Repositories\WithSort;
use Boiler\Traits\Services\HasRepository;
use ReflectionClass;

class CrudService
{
    use HasActivityLogs;
    use HasRepository;
    use WithFilter;
    use WithSort;

    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */

    protected Model $model;

    /**
     * The repository instance.
     *
     * @var GenericRepository
     */
    protected GenericRepository $repository;

    /**
     * Limit the number of items per page.
     *
     * @var integer
     */
    protected int $perPageLimit = 10;

    /**
     * Set the model instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return static
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
        $this->repository = new GenericRepository($model);

        return $this;
    }

    /**
     * Set the repository instance.
     *
     * @param  GenericRepository $repository
     *
     * @return static
     */
    public function setRepository(GenericRepository $repository) : static
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $component
     * @param array $additionalProps
     *
     * @return \Inertia\Response
     */
    public function index(Request $request, string $component, array $props = []) : Response
    {
        return Inertia::render($component, array_merge(
            [
                'queryFilters' => $request->query(),
            ],
            $request->query(),
            $this->getIndexData($request),
            $props
        ));
    }

     /**
     * Show a form to create a resource.
     *
     * @param Request $request
     * @param string $component
     * @param array $additionalProps
     *
     * @return \Inertia\Response
     */
    public function create(Request $request, string $component, array $props = []): Response
    {
        return Inertia::render($component, array_merge(
            $request->query(),
            $props
        ));
    }

     /**
     * Display a specific resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $component
     * @param array $additionalProps
     *
     * @return \Inertia\Response
     */
    public function edit(Request $request, string $component, array $props = []): Response
    {
        return Inertia::render($component, array_merge(
            $request->query(),
            $props,
        ));
    }

    /**
     * Get the index data for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getIndexData(Request $request)
    {
        $results = [];

        if ($request->query('tab') === 'activity_logs') {
            $results = $this->getActivityData($request);
        } elseif (isset($this->repository)) {
            $results = $this->getRepositoryData($request);
            $results['counts']['activityCount'] = $this->getActivityQuery()->count();
        } else {
            $results = [
                'items' => $this->getIndexItems($request->input('per_page', $this->perPageLimit)),
                'counts' => $this->getIndexCounts($request),
            ];
        }

        return $results;
    }

    /**
     * Get the activity data for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getActivityData(Request $request)
    {
        $results = [
            'items' => $this->getActivityLogs($request),
        ];

        /* Add items count */
        if (isset($this->repository)) {
            $this->buildRepository();
            $results['counts'] = $this->getRepositoryCount($this->buildQuery($request));
        } else {
            $results['counts'] = $this->getIndexCounts($request);
        }

        /* Add activity logs count */
        $results['counts']['activityCount'] = $this->getActivityCount($request);

        return $results;
    }

    /**
     * Get the index items for the resource.
     *
     * @param integer $limit
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function getIndexItems(int $limit = 10)
    {
        return $this->model->paginate($limit);
    }

     /**
     * Get the index counts for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function getIndexCounts(Request $request)
    {
        $reflector = new ReflectionClass(get_class($this->model));
        $hasTrashed = $reflector->hasMethod('withTrashed');

        return [
            'activeCount' => $this->model->count(),
            'archivedCount' => $hasTrashed ? $this->model->onlyTrashed()->count() : 0,
            'activityCount' => $this->getActivityQuery()->count(),
        ];

    }

    /**
     * Prepare validated resource data
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     *
     * @return array
     */
    public function prepareData(FormRequest $request)
    {
        return $request->validated();
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(FormRequest $request)
    {
        return $this->model->create($this->prepareData($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Illuminate\Database\Eloquent\Model $item
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(FormRequest $request, Model $item)
    {
        $item->update($this->prepareData($request));

        return $item;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function archive(Model $item)
    {
        $item->delete();

        return $item;
    }

     /**
     * Restore the specified resource from storage.
     *
     * @param \Illuminate\Database\Eloquent\Model|string|int $resource
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function restore(Model | string | int $resource)
    {
        if ($resource instanceof Model === false) {
            $resource = $this->model->onlyTrashed()->findOrFail($resource);
        }

        $resource->restore();

        return $resource;
    }


}
