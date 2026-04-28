<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use App\Zone\Application\DeleteZone\DeleteZone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DeleteZoneController
{
    public function __construct(private DeleteZone $useCase) {}

    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        try {
            $restaurantUuid = $request->header('X-Restaurant-Id');

            if (!$restaurantUuid) {
                return new JsonResponse(['error' => 'Missing X-Restaurant-Id header'], 400);
            }

            ($this->useCase)($uuid, $restaurantUuid);

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 404);
        }
    }
}