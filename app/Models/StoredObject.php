<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class StoredObject extends Model
{
    protected $table = 'objects';

    protected $fillable = [
        'disk',
        'key',
        'original_name',
        'mime_type',
        'size',
        'uploader_id',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->disk === 'public') {
            return asset('storage/' . $this->key);
        }

        return Storage::disk($this->disk)->url($this->key);
    }

    public function existsOnDisk(): bool
    {
        return Storage::disk($this->disk)->exists($this->key);
    }

    protected static function booted(): void
    {
        static::deleted(function (StoredObject $object) {
            Storage::disk($object->disk)->delete($object->key);
        });
    }
}
