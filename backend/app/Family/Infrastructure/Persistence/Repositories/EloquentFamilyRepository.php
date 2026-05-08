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
            $model->update([
                'name' => $family->name(),
                'active' => $family->active(),
                'restaurant_uuid' => $family->restaurantId()->value(),
            ]);
        } else {
            EloquentFamily::create([
                'uuid' => $family->uuid()->value(),
                'restaurant_uuid' => $family->restaurantId()->value(),
                'name' => $family->name(),
                'active' => $family->active(),
            ]);
        }
    }

    public function findByUuid(Uuid $uuid, string $restaurantUuid): ?Family
    {
        $model = EloquentFamily::where('uuid', $uuid->value())
            ->where('restaurant_uuid', $restaurantUuid)
            ->first();

        return $model ? $this->toDomainEntity($model) : null;
    }

    // --- NUEVO MÉTODO PARA VALIDAR DUPLICADOS ---
    public function findByName(string $name, string $restaurantUuid): ?Family
    {
        $model = EloquentFamily::where('name', $name)
            ->where('restaurant_uuid', $restaurantUuid)
            ->first();

        return $model ? $this->toDomainEntity($model) : null;
    }

    public function findAll(string $restaurantUuid, bool $onlyActive = false): array
    {
        $query = EloquentFamily::where('restaurant_uuid', $restaurantUuid);

        if ($onlyActive) {
            $query->where('active', true); 
        }

        $models = $query->get();

        return $models->map(fn($model) => $this->toDomainEntity($model))->toArray();
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
            $model->restaurant_uuid, // Quitamos el Uuid::create manual si ya lo manejas en fromPersistence
            $model->name,
            (bool) $model->active,
            $model->created_at->toDateTimeImmutable(),
            $model->updated_at->toDateTimeImmutable(),
        );
    }
}