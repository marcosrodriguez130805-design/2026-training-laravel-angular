<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use App\Zone\Application\ToggleZoneActive\ToggleZoneActive;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ToggleZoneActiveController
{
    public function __invoke(Request $request, ToggleZoneActive $toggleZoneActive, string $uuid): JsonResponse
    {
        $restaurantUuid = $request->header('X-Restaurant-Id');

        $response = $toggleZoneActive($uuid, $restaurantUuid);

        return response()->json($response->toArray());
    }
}