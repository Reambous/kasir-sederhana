<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids; // Pastikan HasUuids ada di sini

    protected $fillable = [
        'nama',
        'email',
        'password',
        'gambar_object_id',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi POS kita
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function gambar(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StoredObject::class, 'gambar_object_id');
    }

    private array $doomedObjectIds = [];

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            $user->doomedObjectIds = collect([$user->gambar_object_id])
                ->merge($user->products()->pluck('gambar_object_id'))
                ->filter()
                ->unique()
                ->values()
                ->all();
        });

        static::deleted(function (User $user) {
            app(\App\Services\ObjectStorage::class)->deleteKeys($user->doomedObjectIds);
        });
    }
}
