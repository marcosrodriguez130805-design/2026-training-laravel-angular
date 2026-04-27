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
        // Buscamos el ID en el mismo Header que el otro controlador
        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
            return response()->json(['error' => 'Header X-Restaurant-Id is missing'], 400);
        }

        $onlyActive = $request->query('only_active', false);

        // Pasamos el ID al caso de uso de listar
        $zones = $listZones($restaurantUuid, $onlyActive);

        return response()->json($zones, 200);
    }
}