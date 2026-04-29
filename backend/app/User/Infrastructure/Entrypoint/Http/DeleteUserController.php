<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\DeleteUser\DeleteUser;
use Illuminate\Http\JsonResponse;

class DeleteUserController
{
    public function __invoke(string $uuid, DeleteUser $deleteUser): JsonResponse
    {
        $deleteUser($uuid);

        return response()->json(null, 204);
    }
}