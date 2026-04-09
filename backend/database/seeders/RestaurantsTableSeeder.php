<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant;

class RestaurantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buscamos el UUID de un impuesto real (Como haces en Product)
        $taxUuid = \App\Tax\Infrastructure\Persistence\Models\EloquentTax::first()->uuid;

        EloquentRestaurant::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'Restaurante Ejemplo',
            'legal_name' => 'Restaurante Ejemplo S.L.',
            'tax_uuid' => $taxUuid,
            'email' => 'info@restauranteejemplo.com',
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        EloquentRestaurant::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'Otro Restaurante',
            'legal_name' => 'Otro Restaurante S.A.',
            'tax_uuid' => $taxUuid,
            'email' => 'contacto@otrorestaurante.com',
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}