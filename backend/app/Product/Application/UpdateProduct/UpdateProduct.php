<?php

namespace App\Product\Application\UpdateProduct;

use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class UpdateProduct
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function __invoke(
        string $uuid,
        Uuid $familyId,
        Uuid $taxId,
        string $name,
        int $price,
        int $stock,
        ?string $imageSrc
    ): UpdateProductResponse {
        $product = $this->repository->getProduct($uuid);

        if (!$product) {
            throw new \Exception("Product not found", 404);
        }

        // La entidad se encarga de cambiar sus valores internos
        $product->update(
            $familyId,
            $taxId,
            $name,
            $price,
            $stock,
            $imageSrc
        );

        // El repositorio solo persiste el estado actual de la entidad
        $this->repository->update($product);

        return new UpdateProductResponse($product);
    }
}