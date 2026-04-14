<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\ListProducts\ListProducts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListProductsController
{
    public function __invoke(Request $request, ListProducts $listProducts): JsonResponse
    {
        // Obtenemos el restaurant_uuid de la query
        $restaurantUuid = $request->query('restaurant_uuid');
        if (!$restaurantUuid) {
            return response()->json(['error' => 'restaurant_uuid is required'], 400);
        }

        $responses = $listProducts(\App\Shared\Domain\ValueObject\Uuid::create($restaurantUuid));

        return response()->json(
            array_map(fn($response) => $response->toArray(), $responses), 
            200
        );
    }
}