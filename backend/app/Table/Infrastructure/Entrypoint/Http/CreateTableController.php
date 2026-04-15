<?php

namespace App\Table\Infrastructure\Entrypoint\Http;

use App\Table\Application\CreateTable\CreateTable;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CreateTableController
{
    public function __construct(
        private CreateTable $useCase
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            // 1. Validamos la entrada
            $request->validate([
                'restaurant_uuid' => 'required|string',
                'zone_uuid'       => 'required|string',
                'name'            => 'required|string',
            ]);

            // 2. Ejecutamos el caso de uso
            $response = $this->useCase->__invoke(
                Uuid::create($request->input('restaurant_uuid')),
                Uuid::create($request->input('zone_uuid')),
                (string) $request->input('name')
            );

            // 3. Respuesta 201 Created
            return new JsonResponse($response->toArray(), 201);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}