<?php

namespace App\Services;

use App\Models\StoredObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ObjectStorage
{
    public const DISK = 'public';

    public function put(UploadedFile $file, string $folder, ?Model $uploader = null): StoredObject
    {
        $key = $file->store($folder, self::DISK);

        try {
            return StoredObject::create([
                'disk' => self::DISK,
                'key' => $key,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploader_id' => $uploader?->getKey(),
            ]);
        } catch (\Throwable $e) {
            Storage::disk(self::DISK)->delete($key);

            throw $e;
        }
    }

    public function delete(?StoredObject $object): void
    {
        $object?->delete();
    }

    public function deleteKeys(iterable $objectIds): void
    {
        StoredObject::whereIn('id', collect($objectIds)->filter()->unique())->get()
            ->each->delete();
    }
}
