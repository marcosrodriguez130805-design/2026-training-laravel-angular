<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\CreateFamily\CreateFamily;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateFamilyController
{
    public function __invoke(Request $request, CreateFamily $createFamily): JsonResponse
    {
        // 1. Validamos solo lo que realmente debe enviar el usuario
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        // 2. Extraemos el restaurante del Header (Contexto seguro)
        $restaurantUuid = $request->header('X-Restaurant-Id');

        $response = $createFamily(
            Uuid::create($restaurantUuid),
            $validated['name'],
            $validated['active']
        );

        return response()->json($response->toArray(), 201);
    }
}