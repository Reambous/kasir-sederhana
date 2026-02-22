<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'gambar', 'barcode', 'jumlah', 'harga_beli', 'harga_jual', 'tanggal_masuk', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function soProducts()
    {
        return $this->hasMany(SoProduct::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
