<?php

namespace App\Tax\Infrastructure\Persistence\Repositories;

use App\Tax\Domain\Entity\Tax;
use App\Tax\Domain\Interfaces\TaxRepositoryInterface;
use App\Tax\Infrastructure\Persistence\Models\EloquentTax;

class EloquentTaxRepository implements TaxRepositoryInterface
{
    public function save(Tax $tax): void
    {
        EloquentTax::updateOrCreate(
            ['uuid' => $tax->uuid()->value()],
            [
                'restaurant_id' => $tax->restaurantId(),
                'name'          => $tax->name(),
                'percentage'    => $tax->percentage(),
                'updated_at'    => now(),
            ]
        );
    }

    public function findByUuid(string $uuid): ?Tax
    {
        $eloquentTax = EloquentTax::where('uuid', $uuid)->first();

        if (!$eloquentTax) {
            return null;
        }

        // Aquí deberías tener un Mapper o un método en la entidad para reconstruirla
        return Tax::fromPrimitives(
            $eloquentTax->uuid,
            $eloquentTax->restaurant_id,
            $eloquentTax->name,
            $eloquentTax->percentage
        );
    }

    public function delete(string $uuid): void
    {
        // Gracias a SoftDeletes en el modelo, esto solo marcará deleted_at
        EloquentTax::where('uuid', $uuid)->delete();
    }

    public function listAll(int $restaurantId): array
    {
    // Seguimos la misma estructura de Family
        return EloquentTax::where('restaurant_id', $restaurantId)
            ->get()
            ->map(fn($model) => $this->toDomainEntity($model))
            ->toArray();
    }

    private function toDomainEntity(EloquentTax $model): Tax
    {
        return Tax::fromPersistence(
            $model->uuid,
            $model->restaurant_id,
            $model->name,
            $model->percentage,
            new \DateTimeImmutable($model->created_at),
            new \DateTimeImmutable($model->updated_at)
        );
    }
}