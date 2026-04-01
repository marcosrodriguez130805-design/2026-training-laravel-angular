<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\ListUsers\ListUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListUsersController
{
    public function __invoke(Request $request, ListUsers $listUsers): JsonResponse
    {
        $responses = $listUsers();

        return response()->json(
            array_map(fn($response) => $response->toArray(), $responses), 200
        );
    }
}