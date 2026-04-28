<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use App\Zone\Application\ToggleZoneActive\ToggleZoneActive;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ToggleZoneActiveController
{
    public function __construct(private ToggleZoneActive $useCase) {}

    public function __invoke(Request $request, string $uuid): JsonResponse
    {
        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
            return response()->json(['error' => 'X-Restaurant-Id header is required'], 400);
        }

        try {
            $response = ($this->useCase)($uuid, $restaurantUuid);
            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 404);
        }
    }
}