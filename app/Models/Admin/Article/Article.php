<?php

namespace App\Models\Admin\Article;

use App\Models\System\UploadedFile;
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
        'image_id',
    ];

    /**
     * Get the image associated with the branch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image(){
        return $this->belongsTo(UploadedFile::class, 'image_id');
    }
}
