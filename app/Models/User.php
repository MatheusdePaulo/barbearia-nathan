<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

<<<<<<< HEAD
=======
// ADICIONE O 'cpf' AQUI DENTRO DO FILLABLE
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
#[Fillable(['name', 'email', 'password', 'whatsapp', 'birthday', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

<<<<<<< HEAD
=======
    /**
     * Relacionamento: Um usuário possui muitos agendamentos.
     */
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
<<<<<<< HEAD
=======
            'password' => 'hashed',
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
            'birthday' => 'date',
            'is_admin' => 'boolean',
        ];
    }
<<<<<<< HEAD

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
=======
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
}
