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
        Schema::table('services', function (Illuminate\Database\Schema\Blueprint $table) {
            // Adiciona a coluna image após a duração
            $table->string('image')->nullable()->after('duration');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
