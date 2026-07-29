<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            // Adiciona as colunas após a coluna 'image'
            $table->boolean('is_promo')->default(false)->after('image');
            $table->decimal('promo_price', 10, 2)->nullable()->after('is_promo');
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['is_promo', 'promo_price']);
        });
    }
};
