<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\UpdateTax\UpdateTax;
use App\Shared\Domain\ValueObject\Uuid; // 1. Importamos el VO
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateTaxController
{
    public function __invoke(string $uuid, Request $request, UpdateTax $updateTax): JsonResponse
    {
        try {
            // 2. Convertimos los strings de la request en Value Objects
            $response = $updateTax(
                Uuid::create($uuid), // El ID que viene de la URL
                Uuid::create($request->get('restaurant_uuid')), // El ID del restaurante del body
                (string) $request->get('name'),
                (int) $request->get('percentage')
            );

            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            // Un pequeño truco: si es un error de UUID inválido o similar
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}