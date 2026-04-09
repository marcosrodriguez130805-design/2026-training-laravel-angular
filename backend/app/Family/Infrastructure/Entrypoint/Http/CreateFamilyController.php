<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\CreateFamily\CreateFamily;
use App\Family\Application\CreateFamily\CreateFamilyResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateFamilyController
{
    public function __invoke(Request $request, CreateFamily $createFamily): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_uuid' => 'required|string',
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        // Pasamos directamente el UUID del restaurante
        $response = $createFamily(
            \App\Shared\Domain\ValueObject\Uuid::fromString($validated['restaurant_uuid']),
            $validated['name'],
            $validated['active']
        );

        /** @var CreateFamilyResponse $response */
        return response()->json($response->toArray(), 201);
    }
}