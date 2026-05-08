<?php

namespace App\Family\Application\DeleteFamily;

use App\Family\Domain\Exception\FamilyNotFoundException;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class DeleteFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function __invoke(string $uuid, string $restaurantUuid): void
    {
        $family = $this->repository->findByUuid(Uuid::create($uuid), $restaurantUuid);

        if (!$family) {
            throw new FamilyNotFoundException($uuid);
        }

        $products = $this->productRepository->listProducts(
            Uuid::create($restaurantUuid),
            $uuid
        );

        foreach ($products as $product) {
            $this->productRepository->delete($product->uuid()->value());
        }

        $this->repository->delete(Uuid::create($uuid));
    }
}