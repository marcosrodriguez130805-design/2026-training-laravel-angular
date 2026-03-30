<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\UpdateFamily\UpdateFamily;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateFamilyController
{
    public function __invoke(string $uuid, Request $request, UpdateFamily $updateFamily): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        $response = $updateFamily(
            $uuid,
            $validated['name'],
            $validated['active']
        );

        return response()->json($response->toArray(), 200);
    }
}