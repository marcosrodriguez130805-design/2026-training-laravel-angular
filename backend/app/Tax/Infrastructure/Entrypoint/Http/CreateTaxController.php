<?php

namespace App\Tax\Infrastructure\Entrypoint\Http;

use App\Tax\Application\CreateTax\CreateTax;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateTaxController
{
    public function __construct(private CreateTax $createTax) {}

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'percentage' => 'required|integer|min:0',
        ]);

        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
            return new JsonResponse(['error' => 'Missing X-Restaurant-Id header'], 400);
        }

        $response = ($this->createTax)(
            Uuid::create($restaurantUuid),
            $validated['name'], 
            (int) $validated['percentage']
        );

        return new JsonResponse($response->toArray(), 201);
    }
}