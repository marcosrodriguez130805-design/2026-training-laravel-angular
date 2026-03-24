<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Product\Infrastructure\Persistence\Models\EloquentProduct;
use App\Family\Infrastructure\Persistence\Models\EloquentFamily;
use App\Tax\Infrastructure\Persistence\Models\EloquentTax;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EloquentProduct::create([
            'uuid' => Str::uuid()->toString(),
            'family_id' => EloquentFamily::first()->id,
            'tax_id' => EloquentTax::first()->id,
            'image_src' => 'default.png',
            'name' => 'Test Product',
            'price' => 9.99,
            'stock' => 100,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
