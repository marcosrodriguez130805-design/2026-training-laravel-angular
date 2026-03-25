<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Order\Infrastructure\Persistence\Models\EloquentOrder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pedido abierto
        EloquentOrder::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'status' => 'open',
            'table_id' => 1,
            'opened_by_user_id' => 1,
            'closed_by_user_id' => null,
            'diners' => 4,
            'opened_at' => now(),
            'closed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Pedido cerrado
        EloquentOrder::create([
            'uuid' => Str::uuid()->toString(),
            'restaurant_id' => 1,
            'status' => 'invoiced',
            'table_id' => 1,
            'opened_by_user_id' => 1,
            'closed_by_user_id' => 1,
            'diners' => 1,
            'opened_at' => now()->subHours(2),
            'closed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}