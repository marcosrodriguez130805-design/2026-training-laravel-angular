<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\ListProducts\ListProducts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListProductsController
{
    public function __construct(private ListProducts $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $restaurantUuid = $request->header('X-Restaurant-Id');

            if (!$restaurantUuid) {
                return response()->json(['error' => 'X-Restaurant-Id header is required'], 400);
            }

            $familyUuid = $request->query('family_id');
            $products = ($this->useCase)($restaurantUuid, $familyUuid);

            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}