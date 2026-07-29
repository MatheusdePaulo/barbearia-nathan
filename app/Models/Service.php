<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
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

    public function getActivePriceAttribute()
    {
        return $this->is_promo ? $this->promo_price : $this->price;
    }

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_services');
    }
}
