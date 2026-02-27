<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;

    // Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = ['code', 'tanggal_mulai', 'status'];

    // Relasi: 1 Stock Opname berisi banyak SoProduct
    public function soProducts()
    {
        return $this->hasMany(SoProduct::class, 'stock_opname_id');
    }
}
