<?php

namespace App\Product\Application\ListProducts;

use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\Uuid;

final class ListProducts
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function __invoke(\App\Shared\Domain\ValueObject\Uuid $restaurantId): array
    {
        // El repositorio debería filtrar por restaurante
        $products = $this->repository->listProducts($restaurantId);

        return array_map(function (Product $product) {
            return new ListProductsResponse($product);
        }, $products);
    }
}