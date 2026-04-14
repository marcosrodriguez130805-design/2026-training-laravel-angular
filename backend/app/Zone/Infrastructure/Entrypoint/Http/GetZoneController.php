<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use App\Zone\Application\GetZone\GetZone;
use Illuminate\Http\JsonResponse;

final class GetZoneController
{
    public function __invoke(string $uuid, GetZone $getZone): JsonResponse
    {
        $response = $getZone($uuid);

        if (!$response) {
            return response()->json(['error' => 'Zone not found'], 404);
        }

        return response()->json($response->toArray(), 200);
    }
}