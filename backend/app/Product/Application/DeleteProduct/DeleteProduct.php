<?php

namespace App\Product\Application\DeleteProduct;

use App\Product\Domain\Interfaces\ProductRepositoryInterface;

class DeleteProduct
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid): void
    {
        // El repositorio se encarga de la eliminación física o lógica
        $this->repository->delete($uuid);
    }
}