<?php

namespace App\Product\Infrastructure\Persistence\Repositories;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Product\Infrastructure\Persistence\Models\EloquentProduct;
use App\Shared\Domain\ValueObject\Uuid;

final class EloquentProductRepository implements ProductRepositoryInterface
{
   public function save(Product $product): Product
    {
        EloquentProduct::create([
            'uuid'          => $product->uuid()->value(),
            'restaurant_uuid' => $product->restaurantId()->value(),
            'family_uuid'     => $product->familyId()->value(),
            'tax_uuid'        => $product->taxId()->value(),
            'name'          => $product->name(),
            'price'         => $product->price(),
            'stock'         => $product->stock(),
            'active'        => $product->active(),
            'image_src'     => $product->imageSrc(),
            'created_at'    => $product->createdAt()->value(),
            'updated_at'    => $product->updatedAt()->value(),
        ]);

        return $product;
    }

    
    public function listProducts(Uuid $restaurantId, ?string $familyUuid = null): array
    {
        $query = EloquentProduct::where('restaurant_uuid', $restaurantId->value());

        if ($familyUuid) {
            $query->where('family_uuid', $familyUuid);
        }

        $models = $query->get();

        // CAMBIO: Ahora llamamos a toDomain (que es como se llama tu método abajo)
        return $models->map(fn($model) => $this->toDomain($model))->toArray();
    }

    public function getProduct(Uuid $restaurantId, string $uuid): ?Product
    {
        $model = EloquentProduct::where('uuid', $uuid)
            ->where('restaurant_uuid', $restaurantId->value()) // <--- Seguridad extra
            ->first();

        if (!$model) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function update(Product $product): void
    {
        EloquentProduct::where('uuid', $product->uuid()->value())->update([
            'active'     => $product->active(),
            'price'      => $product->price(), // Asegúrate de actualizar lo necesario
            'updated_at' => $product->updatedAt()->value(),
        ]);
    }

    public function delete(string $uuid): void
    {
        EloquentProduct::where('uuid', $uuid)->delete();
    }

    // ... update y delete están bien ...

    private function toDomain(EloquentProduct $model): Product
{
    return Product::fromPersistence(
        (string) $model->uuid,
        (string) $model->restaurant_uuid,
        (string) $model->family_uuid,
        (string) $model->tax_uuid,
        $model->name,
        (int) $model->price,
        (int) $model->stock,
        (bool) $model->active,
        // Forzamos el paso a DateTimeImmutable para que la entidad no se queje
        $model->created_at->toDateTimeImmutable(),
        $model->updated_at->toDateTimeImmutable(),
        $model->image_src
    );
}
}