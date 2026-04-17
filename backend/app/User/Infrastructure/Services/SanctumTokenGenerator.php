<?php

namespace App\User\Infrastructure\Services;

use App\User\Domain\Entity\User;
use App\User\Domain\Interfaces\TokenGeneratorInterface;
use App\User\Infrastructure\Persistence\Models\EloquentUser;

class SanctumTokenGenerator implements TokenGeneratorInterface
{
    public function generate(User $user): string
    {
        // Buscamos el modelo de Eloquent usando el UUID de la entidad
        $eloquentUser = EloquentUser::where('uuid', $user->uuid()->value())->firstOrFail();

        // Sanctum crea el token y lo guarda en la tabla 'personal_access_tokens'
        // El 'plainTextToken' es lo que debes enviar al cliente (solo se ve una vez)
        return $eloquentUser->createToken('api_token')->plainTextToken;
    }
}