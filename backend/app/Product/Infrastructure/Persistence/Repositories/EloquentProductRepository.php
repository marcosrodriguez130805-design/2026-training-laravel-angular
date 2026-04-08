<?php

namespace App\Product\Infrastructure\Persistence\Repositories;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Product\Infrastructure\Persistence\Models\EloquentProduct;

final class EloquentProductRepository implements ProductRepositoryInterface
{
   public function save(Product $product): Product
    {
        EloquentProduct::create([
            'uuid'          => $product->uuid()->value(),
            'restaurant_id' => $product->restaurantId(), // <-- Añadir ->value()
            'family_id'     => $product->familyId(),     // <-- Añadir ->value()
            'tax_id'        => $product->taxId(),        // <-- Añadir ->value()
            'name'          => $product->name(),                  // Si es string plano, déjalo así. Si es VO, usa ->value()
            'price'         => $product->price(),                 // Si es int plano, déjalo así
            'stock'         => $product->stock(),
            'active'        => $product->active(),
            'image_src'     => $product->imageSrc(),
            'created_at'    => $product->createdAt()->value(),
            'updated_at'    => $product->updatedAt()->value(),
        ]);

        return $product;
    }

    public function listProducts(int $restaurantId): array
{
    $eloquentProducts = EloquentProduct::where('restaurant_id', $restaurantId)->get();

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

    private function toDomain(EloquentProduct $model): Product
    {
        return Product::fromPersistence(
            $model->uuid,
            $model->restaurant_id,
            $model->family_id,
            $model->tax_id,
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