<?php

namespace App\Product\Domain\Interfaces;

use App\Product\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): Product;

    public function getProduct(string $uuid): ?Product;

    public function listProducts(int $restaurantID): array;
}