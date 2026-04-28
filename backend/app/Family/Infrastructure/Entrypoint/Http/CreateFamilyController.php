<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\CreateFamily\CreateFamily;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateFamilyController
{
    public function __construct(private CreateFamily $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
            return new JsonResponse(['error' => 'Missing X-Restaurant-Id header'], 400);
        }

        $response = ($this->useCase)(
            Uuid::create($restaurantUuid),
            $validated['name'],
            $validated['active']
        );

        return new JsonResponse($response->toArray(), 201);
    }
}