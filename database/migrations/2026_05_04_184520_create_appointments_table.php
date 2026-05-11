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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // RELACIONAMENTOS:
            // Conecta com o usuário logado (Breeze)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Conecta com o serviço escolhido
            $table->foreignId('service_id')->constrained()->onDelete('cascade');

            // DADOS DO AGENDAMENTO:
            $table->date('date'); // Data (ex: 2026-05-04)
            $table->string('time'); // Horário (ex: 09:30)

            // FINANCEIRO E STATUS:
            $table->decimal('deposit_amount', 8, 2)->default(5.00);
            $table->string('status')->default('pending'); // pending, confirmed, finished, cancelled

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
