<?php

namespace App\Models\Admin\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Boiler\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title',
        'content',
    ];
}
