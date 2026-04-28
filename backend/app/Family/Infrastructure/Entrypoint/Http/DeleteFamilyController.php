<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\DeleteFamily\DeleteFamily;
use Illuminate\Http\JsonResponse;

class DeleteFamilyController
{
    public function __construct(private DeleteFamily $useCase) {}

    public function __invoke(string $uuid): JsonResponse
    {
        try {
            ($this->useCase)($uuid);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}