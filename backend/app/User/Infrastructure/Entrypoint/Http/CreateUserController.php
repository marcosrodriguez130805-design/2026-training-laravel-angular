<?php

namespace App\User\Infrastructure\Entrypoint\Http;

use App\User\Application\CreateUser\CreateUser;
use App\User\Application\CreateUser\CreateUserResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateUserController
{
    public function __invoke(Request $request, CreateUser $createUser): JsonResponse
    {
    $validated = $request->validate([
        'restaurant_id' => 'required|integer',
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|max:255',
        'password'      => 'required|string|min:6',
        'role'          => 'nullable|string|max:50',
        'image_src'     => 'nullable|string|max:255',
        'pin'           => 'nullable|string|max:10',
    ]);

    // Llamamos al caso de uso respetando el orden de CreateUser::__invoke
    $response = $createUser(
        (int) $validated['restaurant_id'],
        $validated['name'],
        $validated['email'],
        $validated['password'],
        $validated['role']      ?? 'user',
        $validated['image_src'] ?? 'default.png',
        $validated['pin']       ?? '0000'
    );

    return response()->json($response->toArray(), 201);
    }
}