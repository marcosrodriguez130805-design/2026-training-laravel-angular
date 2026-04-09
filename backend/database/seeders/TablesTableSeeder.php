<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Table\Infrastructure\Persistence\Models\EloquentTable;

class TablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener UUIDs existentes
        $restaurantUuid = \App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant::first()->uuid;
        $zoneUuid = \App\Zone\Infrastructure\Persistence\Models\EloquentZone::first()->uuid;

        // Ejemplo de mesas para un restaurante y zona
        EloquentTable::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_uuid' => $restaurantUuid,
            'zone_uuid' => $zoneUuid,
            'name' => 'Mesa 1',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        EloquentTable::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_uuid' => $restaurantUuid,
            'zone_uuid' => $zoneUuid,
            'name' => 'Mesa 2',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}