<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\SoftDeletes;
=======
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
use App\Models\User;
use App\Models\Service;

class Appointment extends Model
{
<<<<<<< HEAD
    use HasFactory, SoftDeletes;
=======
    use HasFactory;
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b

    protected $fillable = [
        'user_id',
        'service_id',
        'client_name',
        'date',
        'time',
        'status',
<<<<<<< HEAD
        'deposit_amount',
=======
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        'payment_id',
        'pix_code',
        'pix_qr_64',
    ];

<<<<<<< HEAD
=======
    // Relacionamento com o Usuário/Cliente
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    public function user()
    {
        return $this->belongsTo(User::class);
    }

<<<<<<< HEAD
    // Serviço primário (legado + compatibilidade com relatórios)
=======
    // Relacionamento com o Serviço (Corte, Barba, etc.)
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

<<<<<<< HEAD
    // Todos os serviços do agendamento (múltiplos)
    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_services');
    }

    // Retorna label de exibição com todos os serviços
    public function getServiceLabelAttribute(): string
    {
        $pivot = $this->services;
        if ($pivot->isNotEmpty()) {
            return $pivot->pluck('name')->join(' + ');
        }
        return $this->service?->name ?? ($this->client_name ?? 'Avulso');
    }

    // Duração total considerando todos os serviços
    public function getTotalDurationAttribute(): int
    {
        $pivot = $this->services;
        if ($pivot->isNotEmpty()) {
            return $pivot->sum('duration');
        }
        return $this->service?->duration ?? 30;
    }

    public function getWhatsAppLinkAttribute(): string
    {
        $phone = preg_replace('/\D/', '', $this->user?->whatsapp ?? '');
        $label = $this->service_label;
        $text = "Olá {$this->user?->name}, aqui é do Barber Nathan! Passando para lembrar do seu horário de {$label} hoje às {$this->time}. Confirmado?";
=======
    public function getWhatsAppLinkAttribute()
    {
        $phone = preg_replace('/\D/', '', $this->user->whatsapp); // Limpa o número
        $time = $this->time;
        $service = $this->service->name;

        $text = "Olá {$this->user->name}, aqui é do Barber Nathan! Passando para lembrar do seu horário de {$service} hoje às {$time}. Confirmado?";

>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        return "https://wa.me/55{$phone}?text=" . urlencode($text);
    }
}
