<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\ListTaxes\ListTaxes;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ListTaxesController
{
    public function __construct(private ListTaxes $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $restaurantUuid = $request->header('X-Restaurant-Id');

            if (!$restaurantUuid) {
                return response()->json(['error' => 'X-Restaurant-Id header is required'], 400);
            }

            $response = ($this->useCase)($restaurantUuid);
            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}