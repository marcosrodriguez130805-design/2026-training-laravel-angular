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
            // 1. Validamos (ya no pedimos restaurant_uuid en el body)
            $request->validate([
                'zone_uuid' => 'required|string',
                'name'      => 'required|string',
            ]);

            // 2. Extraemos el Restaurante del Header
            $restaurantHeader = $request->header('X-Restaurant-Id');
            
            if (!$restaurantHeader) {
                return new JsonResponse(['error' => 'Missing X-Restaurant-Id header'], 400);
            }

            // 3. Ejecutamos el caso de uso
            $response = $this->useCase->__invoke(
                Uuid::create($restaurantHeader),
                Uuid::create($request->input('zone_uuid')),
                (string) $request->input('name')
            );

            return new JsonResponse($response->toArray(), 201);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}