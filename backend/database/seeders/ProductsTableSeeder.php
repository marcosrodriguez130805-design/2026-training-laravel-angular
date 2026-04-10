<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Product\Infrastructure\Persistence\Models\EloquentProduct;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener UUIDs existentes de la base de datos
        $restaurantUuid = \App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant::first()->uuid;
        $familyUuid = \App\Family\Infrastructure\Persistence\Models\EloquentFamily::first()->uuid;
        $taxUuid = \App\Tax\Infrastructure\Persistence\Models\EloquentTax::first()->uuid;

        // Ejemplo de productos
        EloquentProduct::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_uuid' => $restaurantUuid,
            'family_uuid' => $familyUuid,
            'tax_uuid' => $taxUuid,
            'image_src' => 'default.png',
            'name' => 'Producto de prueba 1',
            'price' => 1500,
            'stock' => 50,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        EloquentProduct::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_uuid' => $restaurantUuid,
            'family_uuid' => $familyUuid,
            'tax_uuid' => $taxUuid,
            'image_src' => 'default.png',
            'name' => 'Producto de prueba 2',
            'price' => 2500,
            'stock' => 30,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}