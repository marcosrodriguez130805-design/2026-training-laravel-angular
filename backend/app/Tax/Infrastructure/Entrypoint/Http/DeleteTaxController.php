<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\DeleteTax\DeleteTax;
use Illuminate\Http\JsonResponse;

class DeleteTaxController
{
    public function __construct(
        private DeleteTax $deleteTax
    ) {}

    public function __invoke(string $uuid): JsonResponse
    {
        ($this->deleteTax)($uuid);

        return new JsonResponse(null, 204); // 204 No Content es el estándar para deletes exitosos
    }
}