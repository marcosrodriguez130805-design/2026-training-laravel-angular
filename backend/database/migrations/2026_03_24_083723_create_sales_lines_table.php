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
        Schema::create('sales_lines', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relaciones con UUIDs
            $table->uuid('restaurant_id');
            $table->uuid('sale_id');
            $table->uuid('order_line_id');
            $table->uuid('user_id')->nullable();

            $table->integer('quantity');
            $table->integer('price');
            $table->integer('tax_percentage');
            $table->timestamps();
            $table->softDeletes();

            // Claves foráneas
            $table->foreign('restaurant_id')->references('uuid')->on('restaurants');
            $table->foreign('sale_id')->references('uuid')->on('sales');
            $table->foreign('order_line_id')->references('uuid')->on('order_lines');
            $table->foreign('user_id')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_lines');
    }
};