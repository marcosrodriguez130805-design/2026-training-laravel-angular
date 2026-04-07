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
        // Por ahora solo filtramos por el restaurant_id que venga en la query
        $restaurantId = (int) $request->query('restaurant_id', 1);

        $responses = $listTaxes($restaurantId);

        return response()->json(
            array_map(fn($response) => $response->toArray(), $responses), 
            200
        );
    }
}