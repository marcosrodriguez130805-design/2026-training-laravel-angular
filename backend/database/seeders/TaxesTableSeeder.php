<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        EloquentTax::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'Test Tax',
            'percentage' => 21,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
