<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use App\Zone\Application\DeleteZone\DeleteZone;
use Illuminate\Http\JsonResponse;

final class DeleteZoneController
{
    public function __invoke(string $uuid, DeleteZone $deleteZone): JsonResponse
    {
        try {
            $deleteZone($uuid);

            // Devolvemos 204 No Content (estándar para deletes exitosos)
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}