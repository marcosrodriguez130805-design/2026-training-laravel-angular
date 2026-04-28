<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use Illuminate\Http\Request;
use App\Zone\Application\GetZone\GetZone;
use Illuminate\Http\JsonResponse;

final class GetZoneController
{
    public function __construct(private GetZone $useCase) {}

    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
            return response()->json(['error' => 'Restaurant ID is required'], 400);
        }

        try {
            $response = ($this->useCase)($uuid, $restaurantUuid);
            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 404);
        }
    }
}