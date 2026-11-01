<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'gambar_object_id', 'barcode', 'jumlah', 'harga_beli', 'harga_jual', 'tanggal_masuk', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gambar(): BelongsTo
    {
        return $this->belongsTo(StoredObject::class, 'gambar_object_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function soProducts(): HasMany
    {
        return $this->hasMany(SoProduct::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted(): void
    {
        static::deleted(function (Product $product) {
            app(\App\Services\ObjectStorage::class)->delete($product->gambar()->first());
        });
    }
}
