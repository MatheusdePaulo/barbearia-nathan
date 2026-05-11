<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF'); // Para SQLite (seu caso)
        Service::truncate();

        $services = [
            ['name' => 'Corte Masculino', 'description' => 'Corte clássico ou moderno, tesoura e máquina.', 'price' => 30, 'duration' => 30, 'image' => 'masculino.png'],
            ['name' => 'Corte Infantil', 'description' => 'Atendimento dedicado para os pequenos.', 'price' => 30, 'duration' => 20, 'image' => 'infantil.png'],
            ['name' => 'Limpeza de Pele', 'description' => 'Cuidado completo com esfoliação e hidratação.', 'price' => 35, 'duration' => 45, 'image' => 'limpeza.png'],
            ['name' => 'Barba Esculpida', 'description' => 'Alinhamento com navalha e toalha quente.', 'price' => 25, 'duration' => 30, 'image' => 'barba.png'],
            ['name' => 'Limpeza Nasal', 'description' => 'Remoção de pelos e higienização completa.', 'price' => 25, 'duration' => 15, 'image' => 'nazal.png'],
            ['name' => 'Progressiva ou Botox', 'description' => 'Redução de volume e realinhamento dos fios.', 'price' => 70, 'duration' => 90, 'image' => 'botox.png'],
            ['name' => 'Luzes Corte', 'description' => 'Destaque e estilo para o seu cabelo.', 'price' => 90, 'duration' => 120, 'image' => 'LuzesCorte.png'],
            ['name' => 'Nevou Corte', 'description' => 'O tratamento completo para o seu visual.', 'price' => 110, 'duration' => 150, 'image' => 'NevouCorte.png'],
            ['name' => 'Combo Master', 'description' => 'Corte - Barba. Estilo em um só lugar.', 'price' => 50, 'duration' => 60, 'image' => 'CorteBarba.png'],
            ['name' => 'Combo Premium', 'description' => 'Corte - Barba - Sobrancelha completa.', 'price' => 60, 'duration' => 75, 'image' => 'CorteBarbaSobrancelha.png'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
