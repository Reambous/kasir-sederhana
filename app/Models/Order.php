<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasFactory, HasUuids; // Karena Order menggunakan UUID

    protected $fillable = ['code', 'tanggal', 'nama_buyer', 'metode_pembayaran', 'uang_diterima', 'status', 'user_id', 'potongan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
