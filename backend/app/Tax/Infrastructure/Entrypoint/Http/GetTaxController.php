<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\GetTax\GetTax;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // Importante añadir esto

class GetTaxController
{
    public function __invoke(string $uuid, Request $request, GetTax $getTax): JsonResponse
    {
        // 1. Obtenemos el ID del restaurante del header
        $restaurantUuid = $request->header('X-Restaurant-Id');

        // 2. Pasamos ambos UUIDs al Caso de Uso
        $response = $getTax($uuid, $restaurantUuid);

        if (!$response) {
            return response()->json(['error' => 'Tax not found or access denied'], 404);
        }

        return response()->json($response->toArray(), 200);
    }
}