<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoProduct extends Model
{
    use HasFactory;

    protected $fillable = ['stock_opname_id', 'product_id', 'jumlah_awal', 'jumlah_akhir'];

    // Relasi: SoProduct dimiliki oleh 1 Stock Opname
    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }

    // Relasi: SoProduct mengacu pada 1 Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
