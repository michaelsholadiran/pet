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
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedInteger('sale_price')->nullable();
            $table->unsignedInteger('original_price')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->boolean('allow_partial_stock')->default(false);
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('age_min_weeks')->nullable();
            $table->unsignedTinyInteger('age_max_weeks')->nullable();
            $table->string('breed_size')->nullable();
            $table->string('product_type')->nullable();
            $table->string('catalog_type', 32)->default('simple');
            $table->string('short_description')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
