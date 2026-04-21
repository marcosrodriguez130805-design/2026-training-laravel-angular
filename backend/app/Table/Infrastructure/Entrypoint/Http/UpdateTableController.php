<?php

namespace App\Table\Infrastructure\Entrypoint\Http;

use App\Table\Application\UpdateTable\UpdateTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UpdateTableController
{
    public function __construct(
        private UpdateTable $useCase
    ) {}

    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        try {
            $response = ($this->useCase)(
                $uuid,
                (string) $request->get('name')
            );

            return response()->json($response->toArray(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}