<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\DeleteFamily\DeleteFamily;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeleteFamilyController
{
    public function __construct(private DeleteFamily $useCase) {}

    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
            return new JsonResponse(['error' => 'Missing X-Restaurant-Id header'], 400);
        }

        ($this->useCase)($uuid, $restaurantUuid);

        return response()->json(null, 204);
    }
}