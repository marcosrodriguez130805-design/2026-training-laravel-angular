<?php

namespace App\Product\Infrastructure\Entrypoint\Http;

use App\Product\Application\ToggleProductActive\ToggleProductActive;
use Illuminate\Http\JsonResponse;

class ToggleProductActiveController
{
    public function __invoke(string $uuid, ToggleProductActive $toggleActive): JsonResponse
    {
        // En este patrón, si no se encuentra el producto, 
        // la RuntimeException del caso de uso lanzará un 500 o 404 según tu Handler
        $response = $toggleActive($uuid);

        return response()->json($response->toArray(), 200);
    }
}