<?php

namespace App\User\Application\ListUsers;

use App\User\Domain\Interfaces\UserRepositoryInterface;

class ListUsers
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {}

    public function __invoke(): array
    {
        $users = $this->repository->findAll();

        return array_map(
            function($user) {
                return new ListUsersResponse($user);
            },
            $users
        );
    }
}