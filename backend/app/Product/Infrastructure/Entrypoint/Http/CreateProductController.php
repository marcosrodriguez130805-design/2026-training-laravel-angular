<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\CreateProduct\CreateProduct;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CreateProductController
{
    public function __construct(
        private CreateProduct $useCase
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        // 1. Validamos la entrada según el DATA_MODEL de tu migración de products
        $request->validate([
            'restaurant_id' => 'required|integer',
            'family_id'     => 'required|integer',
            'tax_id'        => 'required|integer',
            'name'          => 'required|string',
            'price'         => 'required|integer',
            'stock'         => 'required|integer',
            'active'        => 'boolean',
            'image_src'     => 'nullable|string',
        ]);

        // 2. Ejecutamos el caso de uso
        $response = $this->useCase->__invoke(
            (int) $request->input('restaurant_id'),
            (int) $request->input('family_id'),
            (int) $request->input('tax_id'),
            $request->input('name'),
            (int) $request->input('price'),
            (int) $request->input('stock'),
            (bool) $request->input('active', false), // Valor por defecto false si no viene
            $request->input('image_src')
        );

        // 3. Devolvemos 201 Created con el array formateado del Response
        return new JsonResponse($response->toArray(), 201);
    }
}