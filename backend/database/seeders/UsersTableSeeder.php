<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\User\Infrastructure\Persistence\Models\EloquentUser;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EloquentUser::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'role' => 'admin',
            'image_src' => 'default.png',
            'name' => 'TestUser',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'pin' => '1234',
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);
    }
}