<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\CreateProduct\CreateProduct;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CreateProductController
{
    public function __construct(
        private CreateProduct $useCase
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'family_uuid' => 'required|string',
            'tax_uuid'    => 'required|string',
            'name'        => 'required|string',
            'price'       => 'required|integer',
            'stock'       => 'required|integer',
            'active'      => 'boolean',
            'image_src'   => 'nullable|string',
        ]);

        // Extraemos el restaurante del Header por seguridad
        $restaurantUuid = $request->header('X-Restaurant-Id');

        $response = ($this->useCase)(
            Uuid::create($restaurantUuid),
            Uuid::create($request->input('family_uuid')),
            Uuid::create($request->input('tax_uuid')),
            $request->input('name'),
            (int) $request->input('price'),
            (int) $request->input('stock'),
            (bool) $request->input('active', false),
            $request->input('image_src')
        );

        return new JsonResponse($response->toArray(), 201);
    }
}