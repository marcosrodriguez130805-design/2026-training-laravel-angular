<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\GetFamily\GetFamily;
use Illuminate\Http\JsonResponse;

class GetFamilyController
{
    public function __invoke(string $uuid, GetFamily $getFamily): JsonResponse
    {
        $response = $getFamily($uuid);

        return response()->json($response->toArray(), 200);
    }
}