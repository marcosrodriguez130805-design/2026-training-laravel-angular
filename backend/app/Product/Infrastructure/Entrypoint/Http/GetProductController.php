<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\GetProduct\GetProduct;
use Illuminate\Http\Request; // Importante añadir Request
use Illuminate\Http\JsonResponse;

class GetProductController
{
    public function __construct(private GetProduct $useCase) {}

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