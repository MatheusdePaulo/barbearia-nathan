<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CustomerTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar 35 clientes aleatórios
        $nomes = ['Marcos', 'Lucas', 'Rafael', 'André', 'Thiago', 'Bruno', 'Felipe', 'Gustavo', 'Rodrigo', 'Diego'];
        $sobrenomes = ['Silva', 'Santos', 'Oliveira', 'Souza', 'Pereira', 'Costa', 'Carvalho', 'Ferreira'];

        for ($i = 1; $i <= 35; $i++) {
            $nomeCompleto = $nomes[array_rand($nomes)] . ' ' . $sobrenomes[array_rand($sobrenomes)] . ' ' . $i;

            $user = User::create([
                'name' => $nomeCompleto,
                'email' => "cliente{$i}@teste.com",
                'password' => Hash::make('password'),
                'whatsapp' => '(85) 9' . mt_rand(8000, 9999) . '-' . mt_rand(1000, 9999),
                'birthday' => Carbon::now()->subYears(mt_rand(18, 50))->subDays(mt_rand(1, 365))->format('Y-m-d'),
                'is_admin' => false,
            ]);

            // 2. Criar 1 agendamento para cada cliente distribuído nos próximos 7 dias
            // 5 clientes por dia para evitar conflitos
            $diaDoAgendamento = floor(($i - 1) / 5); // 0 a 6 (uma semana)
            $horaDoAgendamento = 9 + (($i - 1) % 5); // Inicia as 9h, 10h, 11h...

            Appointment::create([
                'user_id' => $user->id,
                'service_id' => Service::inRandomOrder()->first()->id ?? 1,
                'date' => Carbon::today()->addDays($diaDoAgendamento)->format('Y-m-d'),
                'time' => sprintf('%02d:00', $horaDoAgendamento),
                'status' => 'confirmed',
            ]);
        }
    }
}
