<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Family\Infrastructure\Persistence\Models\EloquentFamily;
// Importamos el modelo de restaurante para buscar un UUID real
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant as Restaurant;
class FamiliesTableSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buscamos un restaurante real para que la clave foránea no falle
        $restaurant = Restaurant::first(); 
        
        if (!$restaurant) {
            $this->command->error("No hay restaurantes en la base de datos. Ejecuta primero el RestaurantSeeder.");
            return;
        }

        // 2. Usamos 'restaurant_uuid' que es como se llama tu columna en la migración
        EloquentFamily::updateOrCreate(
            ['name' => 'Familia Pérez', 'restaurant_uuid' => $restaurant->uuid],
            [
                'uuid' => Str::uuid()->toString(),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        EloquentFamily::updateOrCreate(
            ['name' => 'Familia Gómez', 'restaurant_uuid' => $restaurant->uuid],
            [
                'uuid' => Str::uuid()->toString(),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}