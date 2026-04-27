<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use Illuminate\Http\Request;
use App\Zone\Application\GetZone\GetZone;
use Illuminate\Http\JsonResponse;

final class GetZoneController
{
    public function __invoke(string $uuid, Request $request, GetZone $getZone): JsonResponse
    {
        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
            return response()->json(['error' => 'Restaurant ID is required'], 400);
        }

        $response = $getZone($uuid, $restaurantUuid);

        if (!$response) {
            return response()->json(['error' => 'Zone not found'], 404);
        }

        return response()->json($response->toArray(), 200);
    }
}