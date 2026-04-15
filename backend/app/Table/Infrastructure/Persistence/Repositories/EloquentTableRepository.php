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

    public function listByZone(Uuid $zoneUuid): array
    {
        $models = EloquentTable::where('zone_uuid', $zoneUuid->value())->get();

        return $models->map(fn (EloquentTable $model) => $this->toDomain($model))->toArray();
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
        return new Table(
            Uuid::create($model->uuid),
            Uuid::create($model->restaurant_uuid),
            Uuid::create($model->zone_uuid),
            (string) $model->name
        );
    }
}