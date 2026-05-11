<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Limpa a tabela para não dar erro de duplicata e começar do zero
        // Desativamos as chaves estrangeiras temporariamente para o truncate rodar liso no SQLite/MySQL
        DB::statement('PRAGMA foreign_keys = OFF');
        Product::truncate();
        DB::statement('PRAGMA foreign_keys = ON');

        $products = [
            [
                'name' => 'CONDICIONADOR FOX FOR MEN ICE - EXTRATO DE QUINOA',
                'price' => 45.00,
                'stock' => 10,
                'image' => 'condicionador.png'
            ],
            [
                'name' => 'SHAMPOO 4 EM 1 FOX FOR MEN',
                'price' => 50.00,
                'stock' => 15,
                'image' => 'shampoo.png'
            ],
            [
                'name' => 'BALM PARA BARBA FOX FOR MEN',
                'price' => 40.00,
                'stock' => 12,
                'image' => 'balm.png'
            ],
            [
                'name' => 'POMADA MODELADORA FOX FOR MEN - MATTE',
                'price' => 48.00,
                'stock' => 20,
                'image' => 'pomada.png'
            ],
        ];

        // 2. O loop precisa estar obrigatoriamente dentro da função run
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
