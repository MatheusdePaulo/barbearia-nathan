<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        if (config('database.default') === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        Service::truncate();

        if (config('database.default') === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $services = [
            // Fileira 1
            ['name' => 'Corte Masculino',                  'description' => 'Corte clássico ou moderno, tesoura e máquina.',     'price' => 30,  'duration' => 30,  'image' => 'masculino.webp',             'is_combo' => false],
            ['name' => 'Corte Infantil',                   'description' => 'Atendimento dedicado para os pequenos.',             'price' => 30,  'duration' => 20,  'image' => 'infantil.webp',              'is_combo' => false],
            // Fileira 2
            ['name' => 'Limpeza de Pele',                  'description' => 'Cuidado completo com esfoliação e hidratação.',     'price' => 35,  'duration' => 45,  'image' => 'limpeza.webp',               'is_combo' => false],
            ['name' => 'Hidratação Capilar + Esfoliação',  'description' => 'Hidratação profunda com esfoliação capilar.',       'price' => 20,  'duration' => 30,  'image' => 'hidratacao.webp',            'is_combo' => false],
            // Fileira 3
            ['name' => 'Limpeza Nasal',                    'description' => 'Remoção de pelos e higienização completa.',         'price' => 25,  'duration' => 15,  'image' => 'nazal.webp',                 'is_combo' => false],
            ['name' => 'Barba Esculpida',                  'description' => 'Alinhamento com navalha e toalha quente.',          'price' => 25,  'duration' => 30,  'image' => 'barba.webp',                 'is_combo' => false],
            // Fileira 4
            ['name' => 'Luzes Corte',                      'description' => 'Destaque e estilo para o seu cabelo.',              'price' => 90,  'duration' => 120, 'image' => 'LuzesCorte.webp',            'is_combo' => true],
            ['name' => 'Nevou Corte',                      'description' => 'O tratamento completo para o seu visual.',          'price' => 110, 'duration' => 150, 'image' => 'NevouCorte.webp',            'is_combo' => true],
            // Fileira 5
            ['name' => 'Progressiva ou Botox',             'description' => 'Redução de volume e realinhamento dos fios.',       'price' => 70,  'duration' => 90,  'image' => 'botox.webp',                 'is_combo' => true],
            ['name' => 'Combo Master',                     'description' => 'Corte - Barba. Estilo em um só lugar.',             'price' => 50,  'duration' => 60,  'image' => 'CorteBarba.webp',            'is_combo' => true],
            // Fileira 6
            ['name' => 'Combo Premium 1',                  'description' => 'Social + Barba + Sobrancelha.',                    'price' => 50,  'duration' => 60,  'image' => 'social.webp',                'is_combo' => true],
            ['name' => 'Combo Premium 2',                  'description' => 'Degradê + Barba + Sobrancelha.',                   'price' => 60,  'duration' => 75,  'image' => 'CorteBarbaSobrancelha.webp', 'is_combo' => true],
=======
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
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
