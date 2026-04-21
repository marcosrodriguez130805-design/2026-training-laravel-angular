<?php

namespace App\Table\Infrastructure\Entrypoint\Http;

use App\Table\Application\GetTable\GetTable;
use Illuminate\Http\JsonResponse;
use Exception;

final class GetTableController
{
    public function __construct(
        private GetTable $useCase
    ) {}

    public function __invoke(string $uuid, GetTable $useCase): JsonResponse
{
    try {
        // Obtenemos el objeto Response
        $response = $useCase($uuid);

        // Retornamos llamando a toArray()
        return response()->json($response->toArray(), 200);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 404);
    }
}
}
