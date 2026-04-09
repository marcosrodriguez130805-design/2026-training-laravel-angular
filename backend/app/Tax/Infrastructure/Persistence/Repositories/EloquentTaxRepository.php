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

        return $this->toDomainEntity($eloquentTax);
    }

    public function findByUuidWithRestaurantUuid(string $uuid): ?array
    {
        $eloquentTax = EloquentTax::with('restaurant')
            ->where('uuid', $uuid)
            ->first();

        if (!$eloquentTax) {
            return null;
        }

        return [
            'tax' => $this->toDomainEntity($eloquentTax),
            'restaurant_uuid' => $eloquentTax->restaurant?->uuid,
        ];
    }

    // App/Tax/Infrastructure/Persistence/Repositories/EloquentTaxRepository.php

public function existsByNameAndRestaurant(string $name, string $restaurantUuid, ?string $excludeUuid = null): bool
{
    $query = EloquentTax::where('name', $name)
        ->where('restaurant_uuid', $restaurantUuid);

    // CRUCIAL: Si hay un UUID para excluir, lo aplicamos a la consulta
    if ($excludeUuid !== null) {
        $query->where('uuid', '!=', $excludeUuid);
    }

    return $query->exists();
}

    public function delete(string $uuid): void
    {
        $model = EloquentTax::where('uuid', $uuid)->first();
        if ($model) {
            $model->delete();
        }
    }

    public function listAll(string $restaurantUuid): array
    {
        return EloquentTax::where('restaurant_uuid', $restaurantUuid)
            ->get()
            ->map(fn(EloquentTax $model) => $this->toDomainEntity($model))
            ->toArray();
    }

    private function toDomainEntity(EloquentTax $model): Tax
    {
        return Tax::fromPersistence(
            $model->uuid,
            $model->restaurant_uuid,
            $model->name,
            $model->percentage,
            new \DateTimeImmutable($model->created_at),
            new \DateTimeImmutable($model->updated_at)
        );
    }
}