<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class ExpirarPendentes extends Command
{
    protected $signature = 'appointments:expirar-pendentes';
    protected $description = 'Expira agendamentos pending sem pagamento após 10 minutos';

    public function handle()
    {
        $expirados = Appointment::where('status', 'pending')
            ->where('created_at', '<=', Carbon::now()->subMinutes(10))
            ->get();

        foreach ($expirados as $appointment) {
            $appointment->update(['status' => 'expired']);
        }

        $this->info("Expirados: " . $expirados->count() . " agendamentos.");
    }
}