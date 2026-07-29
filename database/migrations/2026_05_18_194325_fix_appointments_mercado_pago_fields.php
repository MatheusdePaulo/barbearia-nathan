<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('status');
            }
            if (!Schema::hasColumn('appointments', 'pix_code')) {
                $table->text('pix_code')->nullable()->after('payment_id');
            }
            if (!Schema::hasColumn('appointments', 'pix_qr_64')) {
                $table->longText('pix_qr_64')->nullable()->after('pix_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['payment_id', 'pix_code', 'pix_qr_64']);
        });
    }
};