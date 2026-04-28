<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\ListFamilies\ListFamilies;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListFamiliesController
{
    public function __construct(private ListFamilies $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $restaurantUuid = $request->header('X-Restaurant-Id');

            if (!$restaurantUuid) {
                return response()->json(['error' => 'X-Restaurant-Id header is required'], 400);
            }

            $families = ($this->useCase)(
                $restaurantUuid,
                filter_var($request->query('only_active', false), FILTER_VALIDATE_BOOLEAN)
            );

            return response()->json($families, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}

