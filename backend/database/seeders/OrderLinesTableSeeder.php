<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\OrderLine\Infrastructure\Persistence\Models\EloquentOrderLine;

class OrderLinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EloquentOrderLine::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'order_id' => 1,
            'product_id' => 1,
            'user_id' => 1,
            'quantity' => 2,
            'price' => 1500,      // 15,00 €
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
            'user_id' => 1,
            'quantity' => 1,
            'price' => 2300,      // 23,00 €
            'tax_percentage' => 10,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}