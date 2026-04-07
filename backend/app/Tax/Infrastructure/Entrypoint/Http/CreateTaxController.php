<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\CreateTax\CreateTax;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CreateTaxController
{
    public function __construct(
        private CreateTax $useCase
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        // 1. Validamos la entrada según el DATA_MODEL
        $request->validate([
            'restaurant_id' => 'required|integer',
            'name'          => 'required|string',
            'percentage'    => 'required|integer',
        ]);

        // 2. Ejecutamos el caso de uso
        $response = $this->useCase->__invoke(
            (int) $request->input('restaurant_id'),
            $request->input('name'),
            (int) $request->input('percentage')
        );

        // 3. Devolvemos 201 Created
        return new JsonResponse($response->toArray(), 201);
    }
}