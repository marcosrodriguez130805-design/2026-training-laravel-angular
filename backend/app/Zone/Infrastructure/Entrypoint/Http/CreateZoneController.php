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
            // 1. Validamos la entrada
            $request->validate([
                'name'   => 'required|string|max:255',
                'active' => 'required|boolean',
            ]);

            // 2. Extraemos el restaurante del Header
            $restaurantUuid = $request->header('X-Restaurant-Id');

            // 3. Ejecutamos el caso de uso
            $response = $this->useCase->__invoke(
                Uuid::create($restaurantUuid),
                (string) $request->input('name'),
                (bool) $request->input('active')
            );

            // 4. Devolvemos 201 Created
            return new JsonResponse($response->toArray(), 201);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}