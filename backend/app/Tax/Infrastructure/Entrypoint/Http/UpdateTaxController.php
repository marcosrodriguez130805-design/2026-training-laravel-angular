<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\UpdateTax\UpdateTax;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateTaxController
{
    public function __construct(
        private UpdateTax $useCase
    ) {}

    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $request->validate([
            'name'       => 'required|string',
            'percentage' => 'required|integer',
        ]);

        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
            return new JsonResponse(['error' => 'Missing X-Restaurant-Id header'], 400);
        }

        $response = ($this->useCase)(
            Uuid::create($uuid),
            Uuid::create($restaurantUuid),
            (string) $request->input('name'),
            (int) $request->input('percentage')
        );

        return new JsonResponse($response->toArray(), 200);
    }
}