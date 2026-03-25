<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Family\Infrastructure\Persistence\Models\EloquentFamily;

class FamiliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ejemplo de varias familias
        EloquentFamily::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1, // ID de un restaurante existente
            'name' => 'Familia Pérez',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        EloquentFamily::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'name' => 'Familia Gómez',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}