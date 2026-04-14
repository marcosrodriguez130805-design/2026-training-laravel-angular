<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use App\Zone\Application\UpdateZone\UpdateZone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UpdateZoneController
{
    public function __invoke(string $uuid, Request $request, UpdateZone $updateZone): JsonResponse
    {
        try {
            // Validamos que al menos venga el nombre
            $request->validate([
                'name' => 'required|string'
            ]);

            $response = $updateZone(
                $uuid,
                (string) $request->get('name')
            );

            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}