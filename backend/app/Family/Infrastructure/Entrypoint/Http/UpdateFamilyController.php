<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\UpdateFamily\UpdateFamily;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateFamilyController
{
    public function __construct(private UpdateFamily $useCase) {}

    public function __invoke(string $uuid, Request $request): JsonResponse
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
            $uuid,
            $restaurantUuid,
            $validated['name'],
            $validated['active']
        );

        return response()->json($response->toArray(), 200);
    }
}