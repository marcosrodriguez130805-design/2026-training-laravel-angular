<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\ListProducts\ListProducts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListProductsController
{
    public function __invoke(Request $request, ListProducts $listProducts): JsonResponse
    {
        // Obtenemos el restaurant_id de la query, por defecto 1 si no viene
        $restaurantId = (int) $request->query('restaurant_id', 1);

        $responses = $listProducts($restaurantId);

        return response()->json(
            array_map(fn($response) => $response->toArray(), $responses), 
            200
        );
    }
}