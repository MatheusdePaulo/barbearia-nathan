<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Service;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'service_id',
        'client_name',
        'date',
        'time',
        'status',
        'deposit_amount',
        'payment_id',
        'pix_code',
        'pix_qr_64',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Serviço primário (legado + compatibilidade com relatórios)
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

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
        return "https://wa.me/55{$phone}?text=" . urlencode($text);
    }
}
