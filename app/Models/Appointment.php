<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Service;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'date',
        'time',
        'status',
        'payment_id',      // Obrigatório para o MP
        'pix_code',        // Link do copia e cola
        'pix_qr_64',       // Imagem do QR Code
    ];

    // Relacionamento com o Usuário/Cliente
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com o Serviço (Corte, Barba, etc.)
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
