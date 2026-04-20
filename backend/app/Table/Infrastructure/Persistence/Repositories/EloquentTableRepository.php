<?php

namespace App\Table\Infrastructure\Persistence\Repositories;

use App\Table\Domain\Entity\Table;
use App\Table\Domain\Interfaces\TableRepositoryInterface;
use App\Table\Infrastructure\Persistence\Models\EloquentTable;
use App\Shared\Domain\ValueObject\Uuid;

final class EloquentTableRepository implements TableRepositoryInterface
{
    public function save(Table $table): void
    {
        EloquentTable::create([
            'uuid'            => $table->uuid()->value(),
            'restaurant_uuid' => $table->restaurantUuid()->value(),
            'zone_uuid'       => $table->zoneUuid()->value(),
            'name'            => $table->name(),
        ]);
    }

    public function findByUuid(string $uuid): ?Table
    {
        $model = EloquentTable::where('uuid', $uuid)->first();
        return $model ? $this->toDomain($model) : null;
    }

    public function findByZone(string $restaurantUuid, string $zoneUuid): array
{
    // Cambiamos $this->model por EloquentTable
    $models = EloquentTable::where('restaurant_uuid', $restaurantUuid)
        ->where('zone_uuid', $zoneUuid)
        ->get();

    return $models->map(fn($model) => $this->toDomain($model))->toArray();
}

    public function listByRestaurant(Uuid $restaurantUuid): array
    {
        $models = EloquentTable::where('restaurant_uuid', $restaurantUuid->value())->get();
        return $models->map(fn($model) => $this->toDomain($model))->toArray();
    }

    public function update(Table $table): void
    {
        EloquentTable::where('uuid', $table->uuid()->value())->update([
            'name' => $table->name(),
        ]);
    }

    public function delete(string $uuid): void
    {
        EloquentTable::where('uuid', $uuid)->delete();
    }

    private function toDomain(EloquentTable $model): Table
    {
        // Importante: Usamos el método estático y convertimos las fechas de Eloquent
        return Table::fromPersistence(
            (string) $model->uuid,
            (string) $model->restaurant_uuid,
            (string) $model->zone_uuid,
            (string) $model->name,
            $model->created_at->toDateTimeImmutable(),
            $model->updated_at->toDateTimeImmutable()
        );
    }
}