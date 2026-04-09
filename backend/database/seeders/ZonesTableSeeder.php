<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Zone\Infrastructure\Persistence\Models\EloquentZone;

class ZonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener UUID del restaurante existente
        $restaurantUuid = \App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant::first()->uuid;

        // Ejemplo de zonas
        EloquentZone::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_uuid' => $restaurantUuid,
            'name' => 'Zona Interior',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        EloquentZone::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_uuid' => $restaurantUuid,
            'name' => 'Terraza',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}