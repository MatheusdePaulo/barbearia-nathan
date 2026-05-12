<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:send-appointment-reminders')]
#[Description('Command description')]
class SendAppointmentReminders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Calculamos o horário alvo (30 minutos à frente)
        $targetTime = now()->addMinutes(30)->format('H:i');
        $today = now()->format('Y-m-d');

        // Buscamos agendamentos confirmados para hoje e naquele horário
        $appointments = \App\Models\Appointment::with('user', 'service')
            ->where('date', $today)
            ->where('time', $targetTime)
            ->where('status', 'confirmed')
            ->get();

        foreach ($appointments as $ap) {
            $name = $ap->user->name;
            $service = $ap->service->name;
            $phone = $ap->user->whatsapp;

            // Aqui você integrará com sua API de WhatsApp
            $message = "Olá {$name}! Lembrete: seu horário para {$service} é em 30 minutos. Te esperamos!";

            $this->info("Notificação enviada para {$name} - {$phone}");

            // Exemplo de log para teste:
            \Log::info("WhatsApp para {$phone}: {$message}");
        }
    }
}
