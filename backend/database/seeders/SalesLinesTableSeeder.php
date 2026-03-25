<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\SaleLine\Infrastructure\Persistence\Models\EloquentSaleLine;

class SalesLinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ejemplo de línea de venta 1
        EloquentSaleLine::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,  // Debe existir un restaurante
            'sale_id' => 1,        // Debe existir una venta
            'order_line_id' => 1,  // Debe existir un order_line
            'user_id' => 1,        // Debe existir un usuario
            'quantity' => 2,
            'price' => 1500,       // 15,00 €
            'tax_percentage' => 21,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        // Ejemplo de línea de venta 2
        EloquentSaleLine::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'sale_id' => 1,
            'order_line_id' => 2,
            'user_id' => 1,
            'quantity' => 1,
            'price' => 2300,       // 23,00 €
            'tax_percentage' => 10,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}