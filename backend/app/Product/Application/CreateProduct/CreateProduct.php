<?php

namespace App\Product\Application\CreateProduct;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\DomainDateTime;
use Illuminate\Support\Str;

final class CreateProduct
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function __invoke(
        Uuid $restaurantId,
        Uuid $familyId,
        Uuid $taxId,
        string $name,
        int $price,
        int $stock,
        bool $active,
        ?string $imageSrc = null
    ): CreateProductResponse {
        
        $productUuid = Uuid::create(Str::uuid()->toString());
        $now = DomainDateTime::now();

        $product = Product::dddCreate(
            $productUuid,
            $restaurantId,
            $familyId,
            $taxId,
            $name,
            $price,
            $stock,
            $active,
            $now,
            $now,
            $imageSrc
        );

        $this->repository->save($product);

        return new CreateProductResponse($product);
    }
}