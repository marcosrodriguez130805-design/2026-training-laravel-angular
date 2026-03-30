<?php

namespace App\Family\Infrastructure\Persistence\Repositories;

use App\Family\Domain\Entity\Family;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Family\Infrastructure\Persistence\Models\EloquentFamily;
use App\Shared\Domain\ValueObject\Uuid;

class EloquentFamilyRepository implements FamilyRepositoryInterface
{
    public function save(Family $family): void
{
    $model = EloquentFamily::where('uuid', $family->uuid()->value())->first();

    if ($model) {
        // Actualiza
        $model->update([
            'name' => $family->name(),
            'active' => $family->active(),
            'restaurant_id' => $this->resolveRestaurantId($family->restaurantId()),
        ]);
    } else {
        // Crea
        EloquentFamily::create([
            'uuid' => $family->uuid()->value(),
            'restaurant_id' => $this->resolveRestaurantId($family->restaurantId()),
            'name' => $family->name(),
            'active' => $family->active(),
        ]);
    }
}

    public function findByUuid(Uuid $uuid): ?Family
    {
        $model = EloquentFamily::where('uuid', $uuid->value())->first();

        if (!$model) {
            return null;
        }

        return $this->toDomainEntity($model);
    }

    public function findAll(bool $onlyActive): array
    {
        $query = EloquentFamily::query();

        if ($onlyActive) {
            $query->where('active', true);
        }

        return $query->get()
            ->map(fn($model) => $this->toDomainEntity($model))
            ->toArray();
    }

    public function update(Family $family): void
    {
        EloquentFamily::where('uuid', $family->uuid()->value())->update([
            'name' => $family->name(),
            'active' => $family->active(),
        ]);
    }

    public function delete(Uuid $uuid): void
    {
        EloquentFamily::where('uuid', $uuid->value())->delete();
    }

    private function toDomainEntity(EloquentFamily $model): Family
    {
        return Family::fromPersistence(
            $model->uuid,
            (int) $model->restaurant_id,
            $model->name,
            (bool) $model->active,
            $model->created_at->toDateTimeImmutable(),
            $model->updated_at->toDateTimeImmutable(),
        );
    }

    // ✅ Ahora recibe int y opcionalmente valida que exista el restaurante
    private function resolveRestaurantId(int $restaurantId): int
    {
        $exists = \App\Restaurant\Infrastructure\Persistence\Models\EloquentRestaurant::find($restaurantId);
        if (!$exists) {
            throw new \InvalidArgumentException("Restaurant ID $restaurantId does not exist");
        }

        return $restaurantId;
    }
}