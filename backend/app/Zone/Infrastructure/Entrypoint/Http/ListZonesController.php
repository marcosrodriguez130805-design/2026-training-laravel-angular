<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use App\Zone\Application\ListZones\ListZones;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ListZonesController
{
    public function __construct(private ListZones $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $restaurantUuid = $request->header('X-Restaurant-Id');

            if (!$restaurantUuid) {
                return response()->json(['error' => 'Header X-Restaurant-Id is missing'], 400);
            }

            $onlyActive = filter_var($request->query('only_active', false), FILTER_VALIDATE_BOOLEAN);
            $zones = ($this->useCase)($restaurantUuid, $onlyActive);

            return response()->json($zones, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}