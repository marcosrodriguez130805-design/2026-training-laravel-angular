<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Tax\Infrastructure\Persistence\Models\EloquentTax;

class TaxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el UUID del primer restaurante
        $restaurantUuid = \App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant::first()->uuid;

        EloquentTax::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_uuid' => $restaurantUuid,
            'name' => 'IVA General',
            'percentage' => 21,    // 21%
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        EloquentTax::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_uuid' => $restaurantUuid,
            'name' => 'IVA Reducido',
            'percentage' => 10,    // 10%
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}