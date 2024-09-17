<?php

namespace Boiler\Http\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Boiler\Objects\CrudResources;
use Boiler\Services\CrudService;
use Boiler\Traits\Controllers\HandleRedirect;
use Boiler\Traits\Services\WithViewLogs;

abstract class CrudController extends Controller
{
    use HandleRedirect;

     /**
     * The base route for the resource
     *
     * @var string
     */
    abstract protected function route();

     /**
     * The inertia base directory for the resource
     *
     * @var string
     */
    abstract protected function directory();

     /**
     * Service for the resource
     *
     * @return CrudService
     */
    abstract protected function service() : CrudService;

    /**
     * Service for the resource
     *
     * @return CrudResources
     */
    abstract protected function formatter(): CrudResources;

     /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $props
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Inertia\Response
     */
    public function baseIndex(Request $request, array $props = [], ?Model $parent =  null)
    {
        return $this->service()
        ->setJsonResource($this->formatter()->index)
        ->index($request, "{$this->directory()}/Index", $props, $parent);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $props
     *
     * @return \Inertia\Response
     */
    public function baseCreate(Request $request, array $props = [])
    {
        return $this->service()
        ->create($request, "{$this->directory()}/Create", $props);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function baseStore(FormRequest $request)
    {
        $resource = DB::transaction(fn() => $this->service()->store($request));

        return $this->handleRedirect("{$this->route()}.index", $this->buildMessage($resource, 'created'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $props
     * @param string $page
     *
     * @return \Inertia\Response
     */
    public function baseEdit(Request $request, Model $model ,  array $props = [], string $page = 'Edit')
    {
        $props['item'] = $this->formatter()->show::make($model);

        if (in_array(WithViewLogs::class, class_uses($this->service()))) {
            $props += $this->service()->getModelActivity($request, $model);
        }

        return $this->service()
            ->edit($request, "{$this->directory()}/{$page}", $props);
    }

       /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $params
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function baseUpdate(Request $request, Model $model, array $params = [])
    {
        $resource = DB::transaction(fn() => $this->service()->update($request, $model));

        return $this->handleRedirect("{$this->route()}.index", $this->buildMessage($resource, 'updated'), $params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function baseArchive(Model $model)
    {
        DB::transaction(fn() => $this->service()->archive($model));

        return $this->handleRedirect("{$this->route()}.index", $this->buildMessage($model, 'archived'));
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function baseRestore(Model $model)
    {
        $resource = DB::transaction(fn() => $this->service()->restore($model));

        return $this->handleRedirect("{$this->route()}.index", $this->buildMessage($resource, 'restored'), [
            'tab' => 'archived',
        ]);
    }
}
