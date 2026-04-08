<?php

namespace App\Tax\Infrastructure\Persistence\Repositories;

use App\Tax\Domain\Entity\Tax;
use App\Tax\Domain\Interfaces\TaxRepositoryInterface;
use App\Tax\Infrastructure\Persistence\Models\EloquentTax;

class EloquentTaxRepository implements TaxRepositoryInterface
{
    public function save(Tax $tax): Tax
    {
        EloquentTax::create([
            'uuid'          => $tax->uuid()->value(),
            'restaurant_id' => $tax->restaurantId(),
            'name'          => $tax->name(),
            'percentage'    => $tax->percentage(),
            'created_at'    => $tax->createdAt()->format('Y-m-d H:i:s'),
            'updated_at'    => $tax->updatedAt()->format('Y-m-d H:i:s'),
        ]);

        return $tax;
    }

    public function update(Tax $tax): Tax
    {
        // Buscamos el modelo de Eloquent primero
        $model = EloquentTax::where('uuid', $tax->uuid()->value())->firstOrFail();

        // Actualizamos solo los campos necesarios
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

        // Aquí deberías tener un Mapper o un método en la entidad para reconstruirla
        return $this->toDomainEntity($eloquentTax);
    }

    public function delete(string $uuid): void
    {
        $model = EloquentTax::where('uuid', $uuid)->first();
        if ($model) {
            $model->delete();
        }
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