<?php

namespace App\User\Application\CreateUser;

use App\User\Domain\Entity\User;
use App\User\Domain\Interfaces\UserRepositoryInterface;
use App\User\Domain\Interfaces\PasswordHasherInterface;
use App\User\Domain\ValueObject\UserName;
use App\User\Domain\ValueObject\PasswordHash;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\Email; 

class CreateUser
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private PasswordHasherInterface $passwordHasher,
    ) {}

    public function __invoke(
        Uuid $restaurantId,
        string $name,
        string $email,
        string $plainPassword,
        ?string $role = 'user',
        ?string $imageSrc = null,
        ?string $pin = null
    ): CreateUserResponse
    {
        // Creamos el hash de la contraseña
        $hashedPassword = $this->passwordHasher->hash($plainPassword);

        // Creamos el usuario
        $user = User::dddCreate(
    $restaurantId,       // int
    $role,                          // string
    Email::create($email),          // Value Object Email
    UserName::create($name),        // Value Object UserName
    PasswordHash::create($hashedPassword), // Value Object PasswordHash
    $imageSrc,                      // ?string
    $pin                            // string
);

        // Guardamos en el repositorio
        $this->repository->save($user);

        return new CreateUserResponse($user);
    }
}