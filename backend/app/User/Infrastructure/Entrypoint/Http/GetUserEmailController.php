<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\GetUserEmail\GetUserEmail;
use Illuminate\Http\JsonResponse;

class GetUserEmailController
{
    public function __construct(private GetUserEmail $useCase) {}

    public function __invoke(string $email): JsonResponse
    {
        try {
            $response = ($this->useCase)($email);
            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 404);
        }
    }
}