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
        // Ejemplo de mesas para un restaurante y zona
        EloquentTable::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1, // Debe existir un restaurante
            'zone_id' => 1,       // Debe existir una zona
            'name' => 'Mesa 1',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        EloquentTable::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'zone_id' => 1,
            'name' => 'Mesa 2',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}