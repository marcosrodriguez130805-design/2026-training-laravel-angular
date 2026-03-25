<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Sale\Infrastructure\Persistence\Models\EloquentSale;

class SalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ejemplo de ventas
        EloquentSale::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1, // Debe existir un restaurante
            'order_id' => 1,      // Debe existir un pedido
            'user_id' => 1,       // Debe existir un usuario
            'ticket_number' => 1001,
            'value_date' => now(),
            'total' => 4500,      // 45,00 €
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        EloquentSale::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'order_id' => 1,
            'user_id' => 1,
            'ticket_number' => 1002,
            'value_date' => now(),
            'total' => 2300,      // 23,00 €
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}