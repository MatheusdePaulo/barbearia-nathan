<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('description'); // Descrição da entrada ou saída
            $table->decimal('amount', 10, 2); // Valor com duas casas decimais
            $table->enum('type', ['income', 'expense']); // income = entrada, expense = saída
            $table->date('date'); // Data do lançamento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
