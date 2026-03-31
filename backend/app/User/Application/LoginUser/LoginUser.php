<?php

namespace App\User\Application\LoginUser;

use App\User\Domain\Interfaces\UserRepositoryInterface;
use App\User\Domain\Interfaces\PasswordHasherInterface;

class LoginUser
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private PasswordHasherInterface $hasher,
    ) {}

    public function __invoke(string $email, string $password): LoginUserResponse
    {
        $user = $this->repository->findByEmail($email);

        if (!$user) {
            throw new \RuntimeException("User not found with email: $email");
        }

        if (!$this->hasher->check($password, $user->password)) {
            throw new \RuntimeException("Invalid password for email: $email");
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return new LoginUserResponse(
            $user->uuid,
            $user->name,
            $user->email,
            $token
        );
    }
}