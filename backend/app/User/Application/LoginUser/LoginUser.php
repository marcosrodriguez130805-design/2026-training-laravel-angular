<?php

namespace App\User\Application\LoginUser;

use App\User\Domain\Interfaces\UserRepositoryInterface;
use App\User\Domain\Interfaces\PasswordHasherInterface;
use App\User\Domain\Interfaces\TokenGeneratorInterface;

class LoginUser
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private PasswordHasherInterface $hasher,
        private TokenGeneratorInterface $tokenGenerator
    ) {}

    public function __invoke(string $email, string $password): LoginUserResponse
    {
        $user = $this->repository->findByEmail($email);

        if (!$user) {
            throw new \RuntimeException("Usuario no encontrado.");
        }

        // Usamos el método correcto de tu Entidad: passwordHash()
        if (!$this->hasher->check($password, $user->passwordHash())) {
            throw new \RuntimeException("Credenciales inválidas.");
        }

        // Generamos el token usando nuestro nuevo servicio
        $token = $this->tokenGenerator->generate($user);

        return new LoginUserResponse(
            $user->uuid()->value(),
            $user->restaurantUuid()->value(),
            $user->name(),
            $user->email(),
            $token
        );
    }
}