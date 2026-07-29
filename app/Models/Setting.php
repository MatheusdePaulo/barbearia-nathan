<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Importante para permitir o updateOrCreate
    protected $fillable = ['key', 'value'];
}
