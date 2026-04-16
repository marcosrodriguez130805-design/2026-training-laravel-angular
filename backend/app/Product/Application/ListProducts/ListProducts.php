<?php

namespace App\Product\Application\ListProducts;

use App\Product\Domain\Interfaces\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

final class ListProducts
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    // 1. Cambiamos Uuid por string para aceptar lo que viene del controlador
    public function __invoke(string $restaurantUuid, ?string $familyUuid = null): array
    {
        // 2. Creamos el Value Object a partir del string
        $restaurantId = Uuid::create($restaurantUuid);
    
        // 3. Pasamos el OBJETO $restaurantId (el repositorio ya se encarga de sacar el ->value())
        $products = $this->repository->listProducts($restaurantId, $familyUuid);

        return array_map(fn($product) => (new ListProductsResponse($product))->toArray(), $products);
    }
}