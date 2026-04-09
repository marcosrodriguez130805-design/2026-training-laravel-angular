<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\ListTaxes\ListTaxes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListTaxesController
{
    /**
     * Sigue exactamente el patrón de ListFamiliesController
     */
    public function __invoke(Request $request, ListTaxes $listTaxes): JsonResponse
    {
        // Filtramos por el restaurant_uuid que venga en la query
        $restaurantUuid = $request->query('restaurant_uuid', '');

        if (empty($restaurantUuid)) {
            return response()->json(['error' => 'restaurant_uuid is required'], 400);
        }

        $responses = $listTaxes($restaurantUuid);

        return response()->json(
            array_map(fn($response) => $response->toArray(), $responses), 
            200
        );
    }
}