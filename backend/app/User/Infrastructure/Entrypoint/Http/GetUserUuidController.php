<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\GetUser\GetUserUuid;
use Illuminate\Http\JsonResponse;

class GetUserUuidController
{
    // Inyectamos el Caso de Uso "GetUser"
    public function __invoke(string $uuid, GetUserUuid $getUser): JsonResponse
    {
        $response = $getUser($uuid);

        return response()->json($response->toArray(), 200);
    }
}