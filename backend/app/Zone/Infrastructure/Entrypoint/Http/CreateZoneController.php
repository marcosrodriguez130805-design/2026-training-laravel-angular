<?php

namespace App\Zone\Infrastructure\Entrypoint\Http;

use App\Zone\Application\CreateZone\CreateZone;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class CreateZoneController
{
    public function __construct(
        private CreateZone $useCase
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            // 1. Validamos la entrada (siguiendo tu migración de zones)
            $request->validate([
                'restaurant_uuid' => 'required|string',
                'name'            => 'required|string',
            ]);

            // 2. Ejecutamos el caso de uso
            $response = $this->useCase->__invoke(
                Uuid::create($request->input('restaurant_uuid')),
                (string) $request->input('name')
            );

            // 3. Devolvemos 201 Created
            return new JsonResponse($response->toArray(), 201);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}