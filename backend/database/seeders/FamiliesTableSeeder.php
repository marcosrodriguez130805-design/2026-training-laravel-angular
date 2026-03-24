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
        EloquentFamily::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'Test Family',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}