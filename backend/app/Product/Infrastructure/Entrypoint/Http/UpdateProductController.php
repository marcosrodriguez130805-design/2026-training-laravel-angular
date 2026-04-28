<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\UpdateProduct\UpdateProduct;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateProductController
{
    public function __construct(private UpdateProduct $useCase) {}

    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'family_uuid' => 'required|string',
                'tax_uuid'    => 'required|string',
                'name'        => 'required|string|max:255',
                'price'       => 'required|integer|min:0',
                'stock'       => 'required|integer|min:0',
                'image_src'   => 'nullable|string',
            ]);

            $restaurantUuid = $request->header('X-Restaurant-Id');

            if (!$restaurantUuid) {
                return new JsonResponse(['error' => 'Missing X-Restaurant-Id header'], 400);
            }

            $response = ($this->useCase)(
                $restaurantUuid,
                $uuid,
                Uuid::create($validated['family_uuid']),
                Uuid::create($validated['tax_uuid']),
                $validated['name'],
                (int) $validated['price'],
                (int) $validated['stock'],
                $validated['image_src'] ?? null
            );

            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}