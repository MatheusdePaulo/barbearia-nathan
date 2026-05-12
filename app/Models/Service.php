<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'price', 'duration', 'image', 'is_promo', 'promo_price', 'slug'];

    // Helper para pegar o preço real (normal ou promo)
    public function getActivePriceAttribute()
    {
        return $this->is_promo ? $this->promo_price : $this->price;
    }
}
