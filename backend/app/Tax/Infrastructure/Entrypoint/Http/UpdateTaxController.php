<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\UpdateTax\UpdateTax;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateTaxController
{
    public function __invoke(string $uuid, Request $request, UpdateTax $updateTax): JsonResponse
    {
        try {
            $response = $updateTax(
                $uuid,
                $request->get('restaurant_uuid'),
                (string) $request->get('name'),
                (int) $request->get('percentage')
            );

            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}