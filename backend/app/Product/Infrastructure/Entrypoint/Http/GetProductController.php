<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\GetProduct\GetProduct;
use Illuminate\Http\JsonResponse;

class GetProductController
{
    public function __invoke(string $uuid, GetProduct $getProduct): JsonResponse
    {
        $response = $getProduct($uuid);

        if (!$response) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($response->toArray(), 200);
    }
}