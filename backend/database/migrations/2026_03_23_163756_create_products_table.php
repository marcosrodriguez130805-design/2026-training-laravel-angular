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
<<<<<<< HEAD
            $table->foreignId('restaurant_id')->constrained();
=======
>>>>>>> 0ff36e8da2ce67bf5513eceafc1f859ea94304db
            $table->foreignId('family_id')->constrained();
            $table->foreignId('tax_id')->constrained();
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
