<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Executa o comando de lembretes a cada minuto
Schedule::command('app:send-appointment-reminders')->everyMinute();
<<<<<<< HEAD
Schedule::command('appointments:expirar-pendentes')->everyMinute();
=======
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b

// Tarefa para Aniversariantes (roda todo dia às 08:00)
Schedule::call(function () {
    $hoje = now()->format('m-d');
    $aniversariantes = \App\Models\User::whereRaw("strftime('%m-%d', birthday) = ?", [$hoje])->get();

    foreach ($aniversariantes as $user) {
        \Log::info("Parabéns enviado para: {$user->name}");
        // Enviar mensagem de Parabéns via WhatsApp aqui
    }
})->dailyAt('08:00');
