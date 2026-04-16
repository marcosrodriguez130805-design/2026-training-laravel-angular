<?php

namespace App\Tax\Infrastructure\Persistence\Repositories;

use App\Tax\Domain\Entity\Tax;
use App\Tax\Domain\Interfaces\TaxRepositoryInterface;
use App\Tax\Infrastructure\Persistence\Models\EloquentTax;
use App\Shared\Domain\ValueObject\Uuid; // Asegúrate de tener este use

class EloquentTaxRepository implements TaxRepositoryInterface
{
    public function save(Tax $tax): Tax
    {
        EloquentTax::create([
            'uuid'          => $tax->uuid()->value(),
            'restaurant_uuid' => $tax->restaurantId()->value(),
            'name'          => $tax->name(),
            'percentage'    => $tax->percentage(),
            'created_at'    => $tax->createdAt()->format('Y-m-d H:i:s'),
            'updated_at'    => $tax->updatedAt()->format('Y-m-d H:i:s'),
        ]);

        return $tax;
    }

    public function update(Tax $tax): Tax
    {
        $model = EloquentTax::where('uuid', $tax->uuid()->value())->firstOrFail();

        $model->update([
            'name'       => $tax->name(),
            'percentage' => $tax->percentage(),
            'updated_at' => $tax->updatedAt()->format('Y-m-d H:i:s'),
        ]);

        return $tax;
    }

    public function findByUuid(string $uuid): ?Tax
    {
        $eloquentTax = EloquentTax::where('uuid', $uuid)->first();

        if (!$eloquentTax) {
            return null;
        }

        return $this->toDomain($eloquentTax); // Cambiado a toDomain
    }

    public function existsByNameAndRestaurant(string $name, string $restaurantUuid, ?string $excludeUuid = null): bool
    {
        $query = EloquentTax::where('name', $name)
            ->where('restaurant_uuid', $restaurantUuid);

        if ($excludeUuid !== null) {
            $query->where('uuid', '!=', $excludeUuid);
        }

        return $query->exists();
    }

    public function delete(string $uuid): void
    {
        EloquentTax::where('uuid', $uuid)->delete();
    }

    public function listTaxes(Uuid $restaurantId): array
    {
    // Al ser un objeto Uuid, usamos ->value() para la consulta
        $models = EloquentTax::where('restaurant_uuid', $restaurantId->value())->get();

        return $models->map(fn($model) => $this->toDomain($model))->toArray();
    }

    private function toDomain(EloquentTax $model): Tax
    {
        return Tax::fromPersistence(
            $model->uuid,
            $model->restaurant_uuid,
            $model->name,
            (float) $model->percentage, // Cast a float
            new \DateTimeImmutable($model->created_at),
            new \DateTimeImmutable($model->updated_at)
        );
    }
}