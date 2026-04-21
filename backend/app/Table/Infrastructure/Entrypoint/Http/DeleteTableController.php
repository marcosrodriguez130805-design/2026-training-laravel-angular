<?php

namespace App\Table\Infrastructure\Entrypoint\Http;

use App\Table\Application\DeleteTable\DeleteTable;
use Illuminate\Http\JsonResponse;

final class DeleteTableController
{
    public function __construct(
        private DeleteTable $useCase
    ) {}

    public function __invoke(string $uuid): JsonResponse
    {
        ($this->useCase)($uuid);

        return new JsonResponse(null, 204);
    }
}