<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\DeleteFamily\DeleteFamily;
use Illuminate\Http\JsonResponse;

class DeleteFamilyController
{
    public function __invoke(string $uuid, DeleteFamily $deleteFamily): JsonResponse
    {
        $deleteFamily($uuid);

        return response()->json(null, 204);
    }
}