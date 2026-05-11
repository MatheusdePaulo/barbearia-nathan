<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Criar o Administrador (Matheus de Paulo)
        // Definido com o aniversário em 06/05 para testar o badge de hoje
        $admin = User::create([
            'name' => 'Matheus de Paulo',
            'email' => 'admin@barbernathan.com',
            'password' => Hash::make('password'),
            'birthday' => '1995-05-06',
        ]);

        // 2. Criar Clientes de teste via Factory
        $clientes = User::factory(10)->create();

        // 3. Criar os Serviços (ajustado com duration conforme sua migration)
        $corte = Service::create([
            'name' => 'Corte Masculino',
            'price' => 45.00,
            'duration' => 30
        ]);

        $barba = Service::create([
            'name' => 'Barba Imperial',
            'price' => 35.00,
            'duration' => 20
        ]);

        $limpeza = Service::create([
            'name' => 'Limpeza de Pele',
            'price' => 50.00,
            'duration' => 45
        ]);

        // 4. Adicionar Agendamentos (ajustado para colunas 'date' e 'time' separadas)
        Appointment::create([
            'user_id' => $clientes->random()->id,
            'service_id' => $corte->id,
            'date' => now()->format('Y-m-d'),
            'time' => '14:00',
            'status' => 'confirmed',
            'deposit_amount' => 5.00
        ]);

        Appointment::create([
            'user_id' => $clientes->random()->id,
            'service_id' => $barba->id,
            'date' => now()->format('Y-m-d'),
            'time' => '15:30',
            'status' => 'confirmed',
            'deposit_amount' => 5.00
        ]);

        Appointment::create([
            'user_id' => $clientes->random()->id,
            'service_id' => $limpeza->id,
            'date' => now()->format('Y-m-d'),
            'time' => '10:00',
            'status' => 'pending',
            'deposit_amount' => 5.00
        ]);
    }
}
