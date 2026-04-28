<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\CreateUser\CreateUser;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateUserController
{
    public function __construct(
        private CreateUser $useCase
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'password'  => 'required|string|min:6',
            'role'      => 'nullable|string',
            'image_src' => 'nullable|string',
            'pin'       => 'nullable|string',
        ]);

        $restaurantUuid = $request->header('X-Restaurant-Id');

        if (!$restaurantUuid) {
            return new JsonResponse(['error' => 'Missing X-Restaurant-Id header'], 400);
        }

        $response = ($this->useCase)(
            Uuid::create($restaurantUuid),
            $request->input('name'),
            $request->input('email'),
            $request->input('password'),
            $request->input('role') ?? 'user',
            $request->input('image_src'),
            $request->input('pin')
        );

        return new JsonResponse($response->toArray(), 201);
    }
}