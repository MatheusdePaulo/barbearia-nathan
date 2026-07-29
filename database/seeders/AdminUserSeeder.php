<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Professor Gerhard',
            'email' => 'Gerhard@adm.com',
            'password' => Hash::make('Gerhard2024@'),
            // Removi a linha 'role' que estava causando o erro
        ]);
    }
}