<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use App\Zone\Application\ListZones\ListZones;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ListZonesController
{
    public function __invoke(Request $request, ListZones $listZones): JsonResponse
    {
        // 1. Obtenemos el restaurant_uuid de la query string (?restaurant_uuid=...)
        $restaurantUuid = $request->query('restaurant_uuid');

        if (!$restaurantUuid) {
            return response()->json(['error' => 'restaurant_uuid is required'], 400);
        }

        try {
            // 2. Ejecutamos el caso de uso convirtiendo el string a Value Object
            $responses = $listZones(Uuid::create($restaurantUuid));

            // 3. Formateamos la colección de respuestas a array
            return response()->json(
                array_map(fn($response) => $response->toArray(), $responses), 
                200
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}