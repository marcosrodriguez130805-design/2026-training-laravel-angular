<?php

namespace App\Zone\Infrastructure\Persistence\Repositories;

use App\Zone\Domain\Entity\Zone;
use App\Zone\Domain\Interfaces\ZoneRepositoryInterface;
use App\Zone\Infrastructure\Persistence\Models\EloquentZone;
use App\Shared\Domain\ValueObject\Uuid;

final class EloquentZoneRepository implements ZoneRepositoryInterface
{
    public function save(Zone $zone): void
    {
        $model = EloquentZone::where('uuid', $zone->uuid()->value())->first();

        if ($model) {
            $model->update([
                'name' => $zone->name(),
                'active' => $zone->active(),
                'restaurant_uuid' => $zone->restaurantUuid()->value(),
            ]);
        } else {
            EloquentZone::create([
                'uuid' => $zone->uuid()->value(),
                'restaurant_uuid' => $zone->restaurantUuid()->value(),
                'name' => $zone->name(),
                'active' => $zone->active(),
            ]);
        }
    }

    public function findByUuid(Uuid $uuid, string $restaurantUuid): ?Zone
    {
        $model = EloquentZone::where('uuid', $uuid->value())
            ->where('restaurant_uuid', $restaurantUuid)
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function listZones(string $restaurantUuid, bool $onlyActive = false): array
    {
        $query = EloquentZone::where('restaurant_uuid', $restaurantUuid);

        if ($onlyActive) {
            $query->where('active', true);
        }

        $models = $query->get();

        return $models->map(fn($model) => $this->toDomain($model))->toArray();
    }

    /**
     * Convierte el modelo de base de datos a la Entidad de Dominio
     */
    private function toDomain(EloquentZone $model): Zone
    {
        return Zone::fromPersistence(
            $model->uuid,
            $model->restaurant_uuid,
            $model->name,
            (bool) $model->active,
            $model->created_at->toDateTimeImmutable(),
            $model->updated_at->toDateTimeImmutable(),
        );
    }

    public function update(Zone $zone): void
    {
        EloquentZone::where('uuid', $zone->uuid()->value())->update([
            'name' => $zone->name(),
            'active' => $zone->active(),
        ]);
    }

    public function delete(string $uuid): void
    {
        EloquentZone::where('uuid', $uuid)->delete();
    }
}