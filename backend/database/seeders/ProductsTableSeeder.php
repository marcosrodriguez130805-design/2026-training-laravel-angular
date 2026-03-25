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
        // Ejemplo de productos
        EloquentProduct::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'family_id' => 1,
            'tax_id' => 1,
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
            'restaurant_id' => 1,
            'family_id' => 1,
            'tax_id' => 1,
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