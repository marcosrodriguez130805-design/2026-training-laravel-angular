<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\GetProduct\GetProduct;
use Illuminate\Http\Request; // Importante añadir Request
use Illuminate\Http\JsonResponse;

class GetProductController
{
    public function __invoke(Request $request, string $uuid, GetProduct $getProduct): JsonResponse
    {
        // 1. Obtenemos el ID del restaurante del header
        $restaurantUuid = $request->header('X-Restaurant-Id');

        // 2. Validación básica por si el header falta
        if (!$restaurantUuid) {
            return response()->json(['error' => 'X-Restaurant-Id header is required'], 400);
        }

        // 3. Pasamos AMBOS parámetros al caso de uso
        $response = $getProduct($restaurantUuid, $uuid);

        if (!$response) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($response->toArray(), 200);
    }
}