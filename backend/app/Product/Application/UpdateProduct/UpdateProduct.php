<?php

namespace App\Product\Application\UpdateProduct;

use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class UpdateProduct
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function __invoke(
        string $restaurantUuid,
        string $uuid,
        Uuid $familyId,
        Uuid $taxId,
        string $name,
        int $price,
        int $stock,
        ?string $imageSrc
    ): UpdateProductResponse {
        $restaurantId = Uuid::create($restaurantUuid);
        $product = $this->repository->getProduct($restaurantId, $uuid);

        if (!$product) {
            throw new ProductNotFoundException($uuid);
        }

        $product->update(
            $familyId,
            $taxId,
            $name,
            $price,
            $stock,
            $imageSrc
        );

        $this->repository->update($product);

        return new UpdateProductResponse($product);
    }
}