<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Sale\Infrastructure\Persistence\Models\EloquentSale;

class SalesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener un usuario existente
        $userUuid = DB::table('users')->first()->uuid;

        // Obtener un restaurante existente
        $restaurantUuid = DB::table('restaurants')->first()->uuid;

        // Obtener un pedido existente
        $orderUuid = DB::table('orders')->first()->uuid;


        // Crear ventas
        EloquentSale::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => $restaurantUuid,
            'order_id' => $orderUuid,
            'user_id' => $userUuid,
            'ticket_number' => 1001,
            'value_date' => now(),
            'total' => 4500,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        EloquentSale::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => $restaurantUuid,
            'order_id' => $orderUuid,
            'user_id' => $userUuid,
            'ticket_number' => 1002,
            'value_date' => now(),
            'total' => 2300,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}