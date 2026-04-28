<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use App\Zone\Application\UpdateZone\UpdateZone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UpdateZoneController
{
    public function __construct(private UpdateZone $useCase) {}

    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $restaurantUuid = $request->header('X-Restaurant-Id');

            if (!$restaurantUuid) {
                return new JsonResponse(['error' => 'Missing X-Restaurant-Id header'], 400);
            }

            $response = ($this->useCase)(
                $uuid,
                $restaurantUuid,
                (string) $request->input('name')
            );

            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}