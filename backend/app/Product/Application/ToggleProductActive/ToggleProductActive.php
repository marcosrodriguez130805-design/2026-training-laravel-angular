<?php

namespace App\Product\Application\ToggleProductActive;

use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class ToggleProductActive
{
    public function __construct(
        private ProductRepositoryInterface $repository,
    ) {}

    public function __invoke(string $restaurantUuid, string $uuid): ToggleProductActiveResponse
    {
        $restaurantId = Uuid::create($restaurantUuid);

        $product = $this->repository->getProduct($restaurantId, $uuid);

        if (!$product) {
            throw new ProductNotFoundException($uuid);
        }

        $product->toggleActive();
        $this->repository->update($product);

        return new ToggleProductActiveResponse($product);
    }
}