<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\ListTaxes\ListTaxes;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ListTaxesController
{
    public function __invoke(Request $request, ListTaxes $listTaxes): JsonResponse
{
    $restaurantUuid = $request->header('X-Restaurant-Id');
    
    // 1. Obtenemos el objeto Response del Caso de Uso
    $response = $listTaxes($restaurantUuid);

    // 2. IMPORTANTE: Llamamos a ->toArray() para que Laravel reciba el array
    return response()->json($response->toArray(), 200);
}
}