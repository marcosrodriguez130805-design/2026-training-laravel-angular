<?php

namespace App\Product\Domain\Interfaces;

use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\Uuid;

interface ProductRepositoryInterface
{
    public function save(Product $product): Product;

    public function getProduct(string $uuid): ?Product;

    public function listProducts(Uuid $restaurantId): array;

    public function update(Product $product): void;

    public function delete(string $uuid): void;
}