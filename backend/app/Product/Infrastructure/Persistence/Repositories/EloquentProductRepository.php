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

    public function listProducts(Uuid $restaurantId): array
{
    $eloquentProducts = EloquentProduct::where('restaurant_uuid', $restaurantId->value())->get();

    return $eloquentProducts->map(fn (EloquentProduct $model) => $this->toDomain($model))->toArray();
}

    public function getProduct(string $uuid): ?Product
    {
        $model = EloquentProduct::where('uuid', $uuid)->first();

        if (!$model) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function update(Product $product): void
    {
        EloquentProduct::where('uuid', $product->uuid()->value())->update([
            'active' => $product->active(),
            'updated_at' => $product->updatedAt()->value(),
        ]);
    }

    public function delete(string $uuid): void
    {
        EloquentProduct::where('uuid', $uuid)->delete();
    }

    private function toDomain(EloquentProduct $model): Product
    {
        return Product::fromPersistence(
            $model->uuid,
            Uuid::fromString($model->restaurant_uuid),
            Uuid::fromString($model->family_uuid),
            Uuid::fromString($model->tax_uuid),
            $model->name,
            $model->price,
            $model->stock,
            $model->active,
            $model->image_src,
            new \DateTimeImmutable($model->created_at),
            new \DateTimeImmutable($model->updated_at)
        );
    }
}