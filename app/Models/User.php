<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'whatsapp', 'birthday', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birthday' => 'date',
            'is_admin' => 'boolean',
        ];
    }

    public function setPasswordAttribute(?string $value): void
    {
        if ($value !== null) {
            $this->attributes['password'] = \Illuminate\Support\Facades\Hash::isHashed($value)
                ? $value
                : \Illuminate\Support\Facades\Hash::make($value);
        } else {
            $this->attributes['password'] = null;
        }
    }
}
