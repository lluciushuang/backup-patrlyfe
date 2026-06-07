<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_tiers', function (Blueprint $table) {
            // Tambah price_level column dengan default 1 (eceran)
            $table->integer('price_level')->default(1)->after('discount_percent');
        });
    }

    public function down(): void
    {
        Schema::table('customer_tiers', function (Blueprint $table) {
            $table->dropColumn('price_level');
        });
    }
};
