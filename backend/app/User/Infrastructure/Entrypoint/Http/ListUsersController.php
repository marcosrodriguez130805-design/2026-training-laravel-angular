<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\ListUsers\ListUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListUsersController
{
    public function __construct(private ListUsers $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        $responses = ($this->useCase)();

        return response()->json(
            array_map(fn($response) => $response->toArray(), $responses),
            200
        );
    }
}