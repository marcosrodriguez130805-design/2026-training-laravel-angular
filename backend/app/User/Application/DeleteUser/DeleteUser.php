<?php

namespace App\User\Application\DeleteUser;

use App\User\Domain\Interfaces\UserRepositoryInterface;

class DeleteUser
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid): void
    {
        // 1. Buscamos al usuario pasando el string directamente
        $user = $this->repository->findByUuid($uuid);

        if (!$user) {
            throw new \RuntimeException("User not found with uuid: $uuid");
        }

        // 2. Llamamos al delete pasando el string directamente
        // NO uses Uuid::create($uuid) aquí, o volverá a fallar el tipo
        $this->repository->delete($uuid);
    }
}