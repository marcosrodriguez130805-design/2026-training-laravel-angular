<?php

namespace App\Table\Infrastructure\Entrypoint\Http;

use App\Table\Application\ListTablesByZone\ListTablesByZone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ListTablesByZoneController
{
    public function __construct(
        private ListTablesByZone $useCase
    ) {}

    public function __invoke(Request $request, string $zoneUuid): JsonResponse
    {
        try {
            $restaurantHeader = $request->header('X-Restaurant-Id');
            
            if (!$restaurantHeader) {
                return new JsonResponse(['error' => 'Missing X-Restaurant-Id header'], 400);
            }

            // Ejecutamos el caso de uso pasando ambos UUIDs
            $response = ($this->useCase)(
                (string) $restaurantHeader,
                $zoneUuid
            );

            // Al igual que antes, $response ya es un array, no llamamos a ->toArray()
            return new JsonResponse($response, 200);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}