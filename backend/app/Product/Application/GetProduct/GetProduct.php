<?php

namespace App\Product\Application\GetProduct;

use App\Product\Domain\Interfaces\ProductRepositoryInterface;

class GetProduct
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid): ?GetProductResponse
    {
        $product = $this->repository->getProduct($uuid);

        if (!$product) {
            return null;
        }

        return new GetProductResponse($product);
    }
}