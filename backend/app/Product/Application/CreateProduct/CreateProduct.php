<?php

namespace App\Product\Application\CreateProduct;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

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
        // Usamos el método estático que definimos en la Entidad
        $product = Product::dddCreate(
            $restaurantId,
            $familyId,
            $taxId,
            $name,
            $price,
            $stock,
            $active,
            $imageSrc
        );

        $this->repository->save($product);

        return new CreateProductResponse($product);
    }
}
