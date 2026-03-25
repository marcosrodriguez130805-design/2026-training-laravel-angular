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
        // Ejemplo de zonas
        EloquentZone::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1, // ID de un restaurante existente
            'name' => 'Zona Interior',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        EloquentZone::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'name' => 'Terraza',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}