<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\User\Infrastructure\Persistence\Models\EloquentUser;
use App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant as Restaurant;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buscamos el restaurante
        $restaurant = Restaurant::first();

        // 2. Control de seguridad
        if (!$restaurant) {
            $this->command->error("No se pudo crear el usuario: No existe ningún restaurante en la base de datos.");
            return;
        }

        // 3. Usamos updateOrCreate para poder re-ejecutar el seeder sin errores
        EloquentUser::updateOrCreate(
            ['email' => 'test@example.com'], // Si el email ya existe, actualiza los datos
            [
                'uuid' => Str::uuid()->toString(),
                'restaurant_uuid' => $restaurant->uuid, // El UUID real del restaurante
                'role' => 'admin',
                'image_src' => 'default.png',
                'name' => 'TestUser',
                'password' => Hash::make('password'),
                'pin' => '1234',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $this->command->info("Usuario vinculado al restaurante: {$restaurant->uuid}");
    }
}