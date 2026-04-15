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
        EloquentZone::create([
            'uuid'            => $zone->uuid()->value(),
            'restaurant_uuid' => $zone->restaurantUuid()->value(),
            'name'            => $zone->name(),
        ]);
    }

    public function findByUuid(Uuid $uuid, string $restaurantUuid): ?Zone 
{
    $model = EloquentZone::where('uuid', $uuid->value())
        ->where('restaurant_uuid', $restaurantUuid) // Aquí es donde fallaba
        ->first();

    return $model ? $this->toDomain($model) : null;
}

    public function listZones(string $restaurantUuid): array
    {
        $models = EloquentZone::where('restaurant_uuid', $restaurantUuid)->get();

        return $models->map(fn($model) => $this->toDomain($model))->toArray();
    }

    /**
     * Convierte el modelo de base de datos a la Entidad de Dominio
     */
    private function toDomain(EloquentZone $model): Zone
    {
        return new Zone(
            Uuid::create($model->uuid),
            Uuid::create($model->restaurant_uuid),
            (string) $model->name
        );
    }

    // En EloquentZoneRepository.php

    public function update(Zone $zone): void
    {
        EloquentZone::where('uuid', $zone->uuid()->value())
        ->update([
            'name' => $zone->name(),
        ]);
    }

    public function delete(string $uuid): void
    {
        EloquentZone::where('uuid', $uuid)->delete();
    }
}