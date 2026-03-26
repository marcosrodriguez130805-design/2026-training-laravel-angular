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
<<<<<<< HEAD
            $table->foreignId('restaurant_id')->constrained();
            $table->foreignId('sale_id')->constrained();
            $table->foreignId('order_line_id')->constrained('order_lines');
=======
            $table->foreignId('sale_id')->constrained();
            $table->foreignId('product_id')->constrained();
>>>>>>> 0ff36e8da2ce67bf5513eceafc1f859ea94304db
            $table->foreignId('user_id')->constrained();
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('tax_percentage');
            $table->timestamps();
            $table->softDeletes();
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
