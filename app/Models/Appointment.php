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
        'client_name',
        'date',
        'time',
        'status',
        'payment_id',
        'pix_code',
        'pix_qr_64',
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

    public function getWhatsAppLinkAttribute()
    {
        $phone = preg_replace('/\D/', '', $this->user->whatsapp); // Limpa o número
        $time = $this->time;
        $service = $this->service->name;

        $text = "Olá {$this->user->name}, aqui é do Barber Nathan! Passando para lembrar do seu horário de {$service} hoje às {$time}. Confirmado?";

        return "https://wa.me/55{$phone}?text=" . urlencode($text);
    }
}
