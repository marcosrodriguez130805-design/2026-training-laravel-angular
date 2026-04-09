<?php

namespace App\Product\Application\ToggleProductActive;

use App\Product\Domain\Interfaces\ProductRepositoryInterface;

class ToggleProductActive
{
    public function __construct(
        private ProductRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid): ToggleProductActiveResponse
    {
        $product = $this->repository->getProduct($uuid);

        if (!$product) {
            throw new \RuntimeException("Product not found with uuid: $uuid");
        }

        $product->toggleActive();

        $this->repository->update($product);

        return new ToggleProductActiveResponse($product);
    }
}