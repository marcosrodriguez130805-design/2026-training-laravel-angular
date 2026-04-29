<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\GetUser\GetUserUuid;
use Illuminate\Http\JsonResponse;

class GetUserUuidController
{
    public function __construct(private GetUserUuid $useCase) {}

    public function __invoke(string $uuid): JsonResponse
    {
        $response = ($this->useCase)($uuid);
        return response()->json($response->toArray(), 200);
    }
}