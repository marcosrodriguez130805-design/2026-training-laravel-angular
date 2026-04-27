<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use App\Zone\Application\DeleteZone\DeleteZone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DeleteZoneController
{
    public function __invoke(string $uuid, Request $request, DeleteZone $deleteZone): JsonResponse
    {
        try {
            $restaurantUuid = $request->header('X-Restaurant-Id');

            $deleteZone($uuid, $restaurantUuid);

            // Devolvemos 204 No Content (estándar para deletes exitosos)
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}