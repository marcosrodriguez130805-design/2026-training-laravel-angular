<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\OrderLine\Infrastructure\Persistence\Models\EloquentOrderLine;
use Illuminate\Support\Facades\DB;

class OrderLinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtenemos un UUID de un usuario existente
        $userUuid = DB::table('users')->first()->uuid;

        EloquentOrderLine::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'order_id' => 1,
            'product_id' => 1,
            'user_id' => $userUuid,
            'quantity' => 2,
            'price' => 1500,
            'tax_percentage' => 21,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        EloquentOrderLine::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'order_id' => 1,
            'product_id' => 2,
            'user_id' => $userUuid,
            'quantity' => 1,
            'price' => 2300,
            'tax_percentage' => 10,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}