<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\GetTax\GetTax;
use Illuminate\Http\JsonResponse;

class GetTaxController
{
    public function __invoke(string $uuid, GetTax $getTax): JsonResponse
    {
        $response = $getTax($uuid);

        if (!$response) {
            return response()->json(['error' => 'Tax not found'], 404);
        }

        return response()->json($response->toArray(), 200);
    }
}