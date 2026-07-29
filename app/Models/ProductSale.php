<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    // Permitir que o Laravel grave os dados da venda
    protected $fillable = ['product_id', 'quantity', 'total_price', 'date'];

    // Relacionamento para saber qual produto foi vendido
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
