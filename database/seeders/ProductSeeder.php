<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        // Limpa a tabela para evitar duplicatas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
=======
        // 1. Limpa a tabela para não dar erro de duplicata e começar do zero
        // Desativamos as chaves estrangeiras temporariamente para o truncate rodar liso no SQLite/MySQL
        DB::statement('PRAGMA foreign_keys = OFF');
        Product::truncate();
        DB::statement('PRAGMA foreign_keys = ON');
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b

        $products = [
            [
                'name' => 'CONDICIONADOR FOX FOR MEN ICE - EXTRATO DE QUINOA',
                'price' => 45.00,
                'stock' => 10,
<<<<<<< HEAD
                'image' => 'condicionador.webp'
=======
                'image' => 'condicionador.png'
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
            ],
            [
                'name' => 'SHAMPOO 4 EM 1 FOX FOR MEN',
                'price' => 50.00,
                'stock' => 15,
<<<<<<< HEAD
                'image' => 'shampoo.webp'
=======
                'image' => 'shampoo.png'
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
            ],
            [
                'name' => 'BALM PARA BARBA FOX FOR MEN',
                'price' => 40.00,
                'stock' => 12,
<<<<<<< HEAD
                'image' => 'balm.webp'
=======
                'image' => 'balm.png'
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
            ],
            [
                'name' => 'POMADA MODELADORA FOX FOR MEN - MATTE',
                'price' => 48.00,
                'stock' => 20,
<<<<<<< HEAD
                'image' => 'pomada.webp'
            ],
            // NOVOS PRODUTOS - FORCE MEN (Nomes de imagem padronizados em minúsculo)
            [
                'name' => 'POMADA STYLE EFEITO SECO - FORCE MEN',
                'price' => 45.00, 
                'stock' => 15,    
                'image' => 'pomadaforce.webp'
            ],
            [
                'name' => 'BALM DE BARBA - FORCE MEN',
                'price' => 38.00, 
                'stock' => 10,    
                'image' => 'balmforce.webp'
            ],
            [
                'name' => 'SHAMPOO REFRESCANTE - FORCE MEN',
                'price' => 42.00, 
                'stock' => 12,    
                'image' => 'shampooforce.webp'
            ],
        ];

=======
                'image' => 'pomada.png'
            ],
        ];

        // 2. O loop precisa estar obrigatoriamente dentro da função run
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        foreach ($products as $product) {
            Product::create($product);
        }
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
