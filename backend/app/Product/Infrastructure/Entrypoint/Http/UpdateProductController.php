<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\UpdateProduct\UpdateProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateProductController
{
    public function __invoke(string $uuid, Request $request, UpdateProduct $updateProduct): JsonResponse
    {
        try {
            $response = $updateProduct(
                $uuid,
                \App\Shared\Domain\ValueObject\Uuid::fromString($request->get('family_uuid')),
                \App\Shared\Domain\ValueObject\Uuid::fromString($request->get('tax_uuid')),
                (string) $request->get('name'),
                (int) $request->get('price'),
                (int) $request->get('stock'),
                $request->get('image_src') // Puede ser null
            );

            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}