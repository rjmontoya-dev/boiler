<?php

namespace App\Http\Controllers\Admin\Article;

use App\Http\Requests\Admin\Article\ArticleRequest;
use App\Http\Resources\Admin\Article\EditFormat;
use App\Http\Resources\Admin\Article\IndexFormat;
use App\Models\Admin\Article\Article;
use App\Services\Admin\Article\ArticleService;
use App\Services\Global\System\FileUploadService;
use Illuminate\Http\Request;
use Inertia\Response;
use Boiler\Http\Controller\CrudController;
use Boiler\Objects\CrudResources;
use Boiler\Services\CrudService;
use Illuminate\Foundation\Http\FormRequest;

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
                'index' => IndexFormat::class,
                'show' => EditFormat::class,
        ]);
    }

    public function index(Request $request) : Response
    {
        return parent::baseIndex($request);
    }

    public function create(Request $request, array $props = [])
    {
        return parent::baseCreate($request, []);
}

    public function edit(Request $request, Article $article)
    {
        return parent::baseEdit($request, $article);
    }

    public function store(ArticleRequest $request)
    {
        return parent::baseStore($request);
    }

    public function update(ArticleRequest $request)
    {
        return parent::baseStore($request);
    }

    public function archive(Article $article)
    {
        return parent::baseArchive($article);
    }

    public function restore(Article $article)
    {
        return parent::baseRestore($article);
    }
}
