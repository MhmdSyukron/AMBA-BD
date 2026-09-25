<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 12, 2);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->integer('discount_percent')->default(0);
            $table->integer('stock')->default(50);
            $table->decimal('rating', 3, 1)->default(4.8);
            $table->integer('sold_count')->default(120);
            $table->string('store_name')->default('Tokopedia Official');
            $table->string('store_location')->default('Jakarta Selatan');
            $table->boolean('is_official_store')->default(true);
            $table->boolean('is_power_merchant')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_flash_sale')->default(false);
            $table->string('image_url');
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
