<?php

namespace Database\Seeders;

use App\User\Infrastructure\Persistence\Models\EloquentUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RestaurantsTableSeeder::class,
            UsersTableSeeder::class,
            FamiliesTableSeeder::class,
            TaxesTableSeeder::class,
            ZonesTableSeeder::class,
            ProductsTableSeeder::class,
            TablesTableSeeder::class,
        ]);
    }
}
