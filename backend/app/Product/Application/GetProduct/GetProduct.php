<?php

namespace App\Product\Application\GetProduct;

use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class GetProduct
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function __invoke(string $restaurantUuid, string $uuid): ?GetProductResponse
    {
        $restaurantId = Uuid::create($restaurantUuid);

    
        $product = $this->repository->getProduct($restaurantId, $uuid);

        if (!$product) {
        return null;
        }

        return new GetProductResponse($product);
    }
}