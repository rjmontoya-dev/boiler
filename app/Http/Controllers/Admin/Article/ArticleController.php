<?php

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Controller;
use App\Models\Admin\Article\Article;
use App\Services\Admin\Article\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Response;
use Boiler\Http\Controller\CrudController;
use Boiler\Objects\CrudResources;
use Boiler\Services\CrudService;


class ArticleController extends CrudController
{
     /**
     * The base route for the resource
     *
     * @var string
     */
    protected function route() : string
    {
        return 'admin.article';
    }

     /**
     * The inertia base directory for the resource
     *
     * @var string
     */
    protected function directory()
    {
        return 'Admin/Articles';
    }

     /**
     * Service for the resource
     *
     * @return CrudService
     */
    protected function service() : CrudService
    {
        return new ArticleService();
    }

    /**
     * Service for the resource
     *
     * @return CrudResources
     */
    protected function formatter(): CrudResources
    {
        return new CrudResources([

        ]);
    }

    public function index(Request $request) : Response
    {
        dd("hey");
        return parent::baseIndex($request);
    }
}
