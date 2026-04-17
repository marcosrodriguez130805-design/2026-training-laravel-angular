<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\CreateTax\CreateTax;
use App\Tax\Application\CreateTax\CreateTaxRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateTaxController
{
    public function __construct(private CreateTax $createTax) {}

    public function __invoke(Request $request): JsonResponse
    {
        // 1. Extraemos el restaurante del header
        $restaurantUuid = $request->header('X-Restaurant-Id');
        
        // 2. Obtenemos los datos del impuesto del body
        $name = $request->input('name');
        $percentage = (float) $request->input('percentage');

        // 3. Ejecutamos el caso de uso pasando el contexto del restaurante
        $response = ($this->createTax)(
            $name,
            $percentage,
            $restaurantUuid
        );

        return response()->json($response->toArray(), 201);
    }
}