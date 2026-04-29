<?php

namespace App\User\Application\GetUserEmail;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Interfaces\UserRepositoryInterface;

class GetUserEmail
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {}
    
    public function __invoke(string $email): GetUserEmailResponse
    {
        $user = $this->repository->findByEmail($email); 

        if (!$user) {
            throw new UserNotFoundException($email);
        }

        return new GetUserEmailResponse($user);
    }
}