<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\UpdateUser\UpdateUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UpdateUserController
{
    public function __invoke(Request $request, string $uuid, UpdateUser $updateUser): JsonResponse
    {
        $response = $updateUser(
            $uuid,
            $request->input('name'),
            $request->input('email'),
            $request->input('password'),
            $request->input('pin'),
            $request->input('restaurant_id')
        );

        return response()->json($response->toArray(), 200);
    }
}