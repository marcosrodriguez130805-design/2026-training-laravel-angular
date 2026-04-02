<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\SaleLine\Infrastructure\Persistence\Models\EloquentSaleLine;
use Illuminate\Support\Facades\DB;

class SalesLinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $userUuid = DB::table('users')->first()->uuid;

        // Obtener un restaurante existente
        $restaurantUuid = DB::table('restaurants')->first()->uuid;

        $saleUuid = DB::table('sales')->first()->uuid;

        // Obtener un pedido existente
        $orderLineUuid = DB::table('order_lines')->first()->uuid;
        // Ejemplo de línea de venta 1
        EloquentSaleLine::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => $restaurantUuid,  // Debe existir un restaurante
            'sale_id' => $saleUuid,        // Debe existir una venta
            'order_line_id' => $orderLineUuid,  // Debe existir un order_line
            'user_id' => $userUuid,        // Debe existir un usuario
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
            'restaurant_id' => $restaurantUuid,
            'sale_id' => $saleUuid,
            'order_line_id' => $orderLineUuid,
            'user_id' => $userUuid,
            'quantity' => 1,
            'price' => 2300,       // 23,00 €
            'tax_percentage' => 10,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}