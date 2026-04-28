<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\ToggleProductActive\ToggleProductActive;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ToggleProductActiveController
{
    public function __construct(private ToggleProductActive $useCase) {}

    public function __invoke(Request $request, string $uuid): JsonResponse
    {
        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
            return response()->json(['error' => 'X-Restaurant-Id header is required'], 400);
        }

        try {
            $response = ($this->useCase)($restaurantUuid, $uuid);
            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 404);
        }
    }
}