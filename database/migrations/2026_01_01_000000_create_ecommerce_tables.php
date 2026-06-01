<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('item_type');
            $table->string('item_code')->unique();
            $table->string('barcode')->unique();
            $table->string('name');
            $table->string('brand');
            $table->string('unit')->default('PCS');
            $table->decimal('base_price', 15, 2);
            $table->integer('current_stock')->default(0);
            $table->decimal('cashback_percent', 5, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('price_level');
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_prices');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};