<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\GetUserEmail\GetUserEmail;
use Illuminate\Http\JsonResponse;

class GetUserEmailController
{
    public function __invoke(string $email, GetUserEmail $getUserEmail): JsonResponse
    {
        $response = $getUserEmail($email);

        return response()->json($response->toArray(), 200);
    }
}