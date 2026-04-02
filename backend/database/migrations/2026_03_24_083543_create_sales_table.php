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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relaciones con UUIDs
            $table->uuid('restaurant_id');
            $table->uuid('order_id');
            $table->uuid('user_id');

            $table->integer('ticket_number');
            $table->timestamp('value_date')->useCurrent();
            $table->integer('total')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Claves foráneas
            $table->foreign('restaurant_id')->references('uuid')->on('restaurants');
            $table->foreign('order_id')->references('uuid')->on('orders');
            $table->foreign('user_id')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};