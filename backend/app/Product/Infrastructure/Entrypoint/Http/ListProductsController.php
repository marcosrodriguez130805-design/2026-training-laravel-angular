<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\ListProducts\ListProducts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListProductsController
{
    public function __invoke(Request $request, ListProducts $listProducts): JsonResponse
{
    $restaurantUuid = $request->header('X-Restaurant-Id');

    if (!$restaurantUuid) {
        return response()->json(['error' => 'X-Restaurant-Id header is required'], 400);
    }

    // Opcional: permitir filtrar por familia desde la URL (?family_id=...)
    $familyUuid = $request->query('family_id');

    $products = $listProducts($restaurantUuid, $familyUuid);

    return response()->json($products, 200);
}
}