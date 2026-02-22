<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'nama', 'harga_jual', 'jumlah', 'order_id'];

    // Relasi: OrderItem dimiliki oleh 1 Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: OrderItem mengacu pada 1 Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
