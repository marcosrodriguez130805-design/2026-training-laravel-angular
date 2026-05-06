<?php

namespace App\Family\Application\ToggleFamilyActive;

use App\Family\Domain\Exception\FamilyNotFoundException;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class ToggleFamilyActive
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function __invoke(string $uuid, string $restaurantUuid): ToggleFamilyActiveResponse
    {
        $family = $this->repository->findByUuid(Uuid::create($uuid), $restaurantUuid);

        if (!$family) {
            throw new FamilyNotFoundException($uuid);
        }

        $family->toggleActive();
$this->repository->update($family);

$products = $this->productRepository->listProducts(
    Uuid::create($restaurantUuid),
    $uuid
);

foreach ($products as $product) {
    if ($product->active() !== $family->active()) {
        $product->toggleActive();
        $this->productRepository->update($product);
    }
}

        return new ToggleFamilyActiveResponse($family);
    }
}