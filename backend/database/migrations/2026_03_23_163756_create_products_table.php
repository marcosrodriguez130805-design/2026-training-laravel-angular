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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('restaurant_uuid');
            $table->foreign('restaurant_uuid')->references('uuid')->on('restaurants');
            $table->string('family_uuid');
            $table->foreign('family_uuid')->references('uuid')->on('families');
            $table->string('tax_uuid');
            $table->foreign('tax_uuid')->references('uuid')->on('taxes');
            $table->string('image_src')->nullable();
            $table->string('name');
            $table->integer('price');
            $table->integer('stock');
            $table->boolean('active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};