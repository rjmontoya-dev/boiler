<?php

namespace App\Services\Admin\Article;

use App\Models\Admin\Article\Article;
use Boiler\Services\CrudService;

class ArticleService extends CrudService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
         $this->setModel(new Article());
    }
}
