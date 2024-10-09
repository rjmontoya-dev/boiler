<?php

namespace App\Models\System;

use App\Services\Global\System\FileUploadService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Boiler\Models\BaseModel as Model;

class UploadedFile extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'size',
        'mime',
        'extension',
        'file_path',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleted(function (UploadedFile $model): void {
            // when this model resource was deleted, remove the file in the storage as well
            app(FileUploadService::class)->removeFromStorage($model);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTES
    |--------------------------------------------------------------------------
     */

    public function url(): Attribute
    {
        return Attribute::get(fn() => Storage::url($this->file_path));
    }

    /**
     * Get the file size in human-readable format.
     *
     * @return Attribute
     */
    protected function humanSize(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getHumanReadableSize($this->size)
        );
    }

    /**
     * Convert bytes to human-readable format.
     *
     * @param int $bytes
     *
     * @return string
     */
    private function getHumanReadableSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1_024 && $i < 4) {
            $bytes /= 1_024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
