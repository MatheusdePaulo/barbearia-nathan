<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa a tabela para evitar duplicatas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = [
            [
                'name' => 'CONDICIONADOR FOX FOR MEN ICE - EXTRATO DE QUINOA',
                'price' => 45.00,
                'stock' => 10,
                'image' => 'condicionador.webp'
            ],
            [
                'name' => 'SHAMPOO 4 EM 1 FOX FOR MEN',
                'price' => 50.00,
                'stock' => 15,
                'image' => 'shampoo.webp'
            ],
            [
                'name' => 'BALM PARA BARBA FOX FOR MEN',
                'price' => 40.00,
                'stock' => 12,
                'image' => 'balm.webp'
            ],
            [
                'name' => 'POMADA MODELADORA FOX FOR MEN - MATTE',
                'price' => 48.00,
                'stock' => 20,
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

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
