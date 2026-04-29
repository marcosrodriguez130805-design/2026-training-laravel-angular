<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\GetUserEmail\GetUserEmail;
use Illuminate\Http\JsonResponse;

class GetUserEmailController
{
    public function __construct(private GetUserEmail $useCase) {}

    public function __invoke(string $email): JsonResponse
    {
        $response = ($this->useCase)($email);
        return response()->json($response->toArray(), 200);
    }
}