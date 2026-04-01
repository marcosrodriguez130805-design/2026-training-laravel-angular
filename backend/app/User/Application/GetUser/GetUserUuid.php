<?php

namespace App\User\Application\GetUser;

use App\User\Domain\Interfaces\UserRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class GetUserUuid
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {}
    
    public function __invoke(string $uuid): GetUserUuidResponse // Asegúrate de crear GetUserResponse
    {
        // 1. Convertimos el string a VO (Value Object)
        $user = $this->repository->findByUuid($uuid); 

        // 2. Control de errores
        if (!$user) {
            throw new \RuntimeException("User not found with uuid: $uuid");
        }

        return new GetUserUuidResponse($user);
    }
}