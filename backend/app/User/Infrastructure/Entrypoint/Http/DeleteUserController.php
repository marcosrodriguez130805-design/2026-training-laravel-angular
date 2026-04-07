<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\DeleteUser\DeleteUser;
use Illuminate\Http\JsonResponse;

class DeleteUserController
{
    public function __invoke(string $uuid, DeleteUser $deleteUser): JsonResponse
    {
        try {
            $deleteUser($uuid);
            return response()->json(null, 204);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el usuario'], 500);
        }
    }
}