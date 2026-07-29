<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
<<<<<<< HEAD
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'image',
        'is_promo',
        'promo_price',
        'slug',
        'is_combo',
    ];

    protected $casts = [
        'is_promo' => 'boolean',
        'is_combo' => 'boolean',
        'price' => 'decimal:2',
        'promo_price' => 'decimal:2',
    ];

=======
    protected $fillable = ['name', 'description', 'price', 'duration', 'image', 'is_promo', 'promo_price', 'slug'];

    // Helper para pegar o preço real (normal ou promo)
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    public function getActivePriceAttribute()
    {
        return $this->is_promo ? $this->promo_price : $this->price;
    }
<<<<<<< HEAD

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_services');
    }
}
=======
}
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
