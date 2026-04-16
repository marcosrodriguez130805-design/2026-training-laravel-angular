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

        if (!$restaurantUuid) {
            return response()->json(['error' => 'X-Restaurant-Id header is required'], 400);
        }

        $taxes = $listTaxes($restaurantUuid);

        return response()->json($taxes, 200);
    }
}