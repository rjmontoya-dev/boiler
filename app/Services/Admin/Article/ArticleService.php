<?php

namespace App\Services\Admin\Article;

use App\Models\Admin\Article\Article;
use App\Services\System\FileUploadService;
use Boiler\Services\CrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class ArticleService extends CrudService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
         $this->setModel(new Article());
    }

    public function store(FormRequest $request): Model {

        $data = $this->prepareData($request);

        if($request->hasFile(key: 'image')){
            $data['image_id'] = FileUploadService::storeGetId($request->file('image'), 'articles');
        }

        $item  = $this->model->create($data);

        return $item;
    }
}
