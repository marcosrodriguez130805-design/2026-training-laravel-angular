<?php

namespace App\Table\Infrastructure\Entrypoint\Http;

use App\Table\Application\ListTables\ListTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ListTablesController
{
    public function __construct(
        private ListTables $useCase
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $restaurantHeader = $request->header('X-Restaurant-Id');
            
            if (!$restaurantHeader) {
                return new JsonResponse(['error' => 'Missing X-Restaurant-Id header'], 400);
            }

            $zoneUuid = $request->query('zone_uuid');

            // $response ya es un array de arrays formateados por ListTablesResponse
            $response = $this->useCase->__invoke(
                (string) $restaurantHeader,
                $zoneUuid
            );

            // Quitamos el ->toArray() porque $response ya es el array
            return new JsonResponse($response, 200);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}