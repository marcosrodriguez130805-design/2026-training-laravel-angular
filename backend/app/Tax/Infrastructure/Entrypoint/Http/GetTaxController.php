<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\GetTax\GetTax;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // Importante añadir esto

class GetTaxController
{
    public function __construct(private GetTax $useCase) {}

    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
            return response()->json(['error' => 'X-Restaurant-Id header is required'], 400);
        }

        try {
            $response = ($this->useCase)($uuid, $restaurantUuid);
            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 404);
        }
    }
}