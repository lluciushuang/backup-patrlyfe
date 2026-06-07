<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->integer('min_purchase')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_tier_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_tier_id')->constrained()->cascadeOnDelete();
            $table->dateTime('assigned_at');
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'customer_tier_id']);
        });

        Schema::create('product_price_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('tier_type');
            $table->decimal('price', 15, 2);
            $table->timestamps();

            $table->unique(['product_id', 'tier_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_price_tiers');
        Schema::dropIfExists('customer_tier_assignments');
        Schema::dropIfExists('customer_tiers');
    }
};