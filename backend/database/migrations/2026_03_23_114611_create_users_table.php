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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
<<<<<<< HEAD
            $table->foreignId('restaurant_id')->constrained();
=======
>>>>>>> 0ff36e8da2ce67bf5513eceafc1f859ea94304db
            $table->string('role');
            $table->string('image_src')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
<<<<<<< HEAD
            $table->string('pin');
=======
>>>>>>> 0ff36e8da2ce67bf5513eceafc1f859ea94304db
            $table->timestamps();
            $table->softDeletes();
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
