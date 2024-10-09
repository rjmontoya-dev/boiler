<?php

namespace App\Services\System;

use App\Models\System\UploadedFile;
use Illuminate\Http\UploadedFile as HttpUploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload and create record
     *
     * @param \Illuminate\Http\UploadedFile|null $file
     * @param string $folder
     *
     * @return \App\Models\System\UploadedFile|null
     */
    public function store(HttpUploadedFile | null $file, string $folder): UploadedFile | null
    {
        if (!$this->isNewFile($file)) {
            return null;
        }

        $uploadResult = $this->fileUploader($file, $folder);

        return UploadedFile::create([
            'name' => $uploadResult['name'],
            'size' => $uploadResult['size'],
            'mime' => $uploadResult['mime'],
            'extension' => $uploadResult['extension'],
            'file_path' => $uploadResult['path'],
        ]);
    }

    /**
     * Store then get ID
     *
     * @param \Illuminate\Http\UploadedFile|null $file
     * @param string $folder
     *
     * @return int|null
     */
    public static function storeGetId(HttpUploadedFile | null $file, string $folder): int | null
    {
        $file = (new static())->store($file, $folder);
        return $file?->getKey();
    }

    /**
     * Remove file from storage
     *
     * @param \App\Models\System\UploadedFile $file
     *
     * @return void
     */
    public function removeFromStorage(UploadedFile $file): void
    {
        Storage::disk(config('filesystems.default'))->delete($file->file_path);
    }

    /**
     * Determine if the file is a new file for upload.
     *
     * @param mixed $file
     *
     * @return bool
     */
    public function isNewFile(mixed $file): bool
    {
        return $file instanceof HttpUploadedFile;
    }

    /**
     * File uploader
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param mixed $folder
     *
     * @return array
     */
    protected function fileUploader(HttpUploadedFile $file, $folder): array
    {
        $fileHash = explode('.', $file->hashName())[0];
        $ext = $file->getClientOriginalExtension();

        $filePath = $file->storeAs($folder, $fileHash . '.' . $ext, config('filesystems.default'));

        return [
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getClientMimeType(),
            'extension' => $ext,
            'path' => $filePath,
        ];
    }
}
