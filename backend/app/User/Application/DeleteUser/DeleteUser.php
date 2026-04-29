<?php

namespace App\User\Application\DeleteUser;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Interfaces\UserRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class DeleteUser
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid): void
    {
        $uuidVo = Uuid::create($uuid);
        $user = $this->repository->findByUuid($uuidVo);

        if (!$user) {
            throw new UserNotFoundException($uuid);
        }

        $this->repository->delete($uuidVo);
    }
}