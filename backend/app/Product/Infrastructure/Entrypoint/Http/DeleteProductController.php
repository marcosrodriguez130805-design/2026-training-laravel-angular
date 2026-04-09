<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\DeleteProduct\DeleteProduct;
use Illuminate\Http\JsonResponse;

class DeleteProductController
{
    public function __construct(
        private DeleteProduct $deleteProduct
    ) {}

    public function __invoke(string $uuid): JsonResponse
    {
        ($this->deleteProduct)($uuid);

        return new JsonResponse(null, 204);
    }
}