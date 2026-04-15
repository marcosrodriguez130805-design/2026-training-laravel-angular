<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\ListFamilies\ListFamilies;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListFamiliesController
{
    public function __invoke(Request $request, ListFamilies $listFamilies): JsonResponse
    {
        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
        return response()->json(['error' => 'X-Restaurant-Id header is required'], 400);
        }

        // Le pasamos el ID y, si quieres, el filtro de activos desde la query
        $families = $listFamilies(
            $restaurantUuid, 
            $request->query('only_active', false)
        );

        return response()->json($families, 200);
    }
}

