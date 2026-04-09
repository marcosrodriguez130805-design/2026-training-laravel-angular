<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\User\Application\UpdateUser\UpdateUser;

class UpdateUserController
{
    public function __invoke(string $uuid, Request $request, UpdateUser $updateUser): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|string|min:6',
            'pin' => 'nullable|string',
            'restaurant_uuid' => 'nullable|string',
            'role' => 'required|string',
            'image_src' => 'nullable|string',
        ]);

        $response = $updateUser(
            $uuid,
            $validated['name'],
            $validated['email'],
            $validated['password'] ?? null,
            $validated['pin'] ?? null,
            $validated['restaurant_uuid'] ? \App\Shared\Domain\ValueObject\Uuid::fromString($validated['restaurant_uuid']) : null,
            $validated['role'],
            $validated['image_src'] ?? null
        );

        return response()->json($response->toArray(), 200);
    }
}