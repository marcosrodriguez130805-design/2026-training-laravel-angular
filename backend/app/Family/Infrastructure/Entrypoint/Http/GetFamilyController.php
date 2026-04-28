<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use Illuminate\Http\Request;
use App\Family\Application\GetFamily\GetFamily;
use Illuminate\Http\JsonResponse;

class GetFamilyController
{
    public function __construct(private GetFamily $useCase) {}

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