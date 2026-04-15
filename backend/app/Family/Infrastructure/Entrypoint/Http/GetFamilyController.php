<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use Illuminate\Http\Request;
use App\Family\Application\GetFamily\GetFamily;
use Illuminate\Http\JsonResponse;

class GetFamilyController
{
    public function __invoke(string $uuid, Request $request, GetFamily $getFamily): JsonResponse
{
    // 1. Capturamos el ID del restaurante del Header
    $restaurantUuid = $request->header('X-Restaurant-Id');

    if (!$restaurantUuid) {
        return response()->json(['error' => 'X-Restaurant-Id header is required'], 400);
    }

    // 2. Pasamos AMBOS parámetros al caso de uso
    // Antes tenías solo $getFamily($uuid), ahora añadimos el segundo:
    $family = $getFamily($uuid, $restaurantUuid);

    return response()->json($family->toArray(), 200);
}
}