<?php

namespace App\User\Application\GetUser;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Interfaces\UserRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class GetUserUuid
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {}
    
    public function __invoke(string $uuid): GetUserUuidResponse
    {
        $uuidVo = Uuid::create($uuid);
        $user = $this->repository->findByUuid($uuidVo);

        if (!$user) {
            throw new UserNotFoundException($uuid);
        }

        return new GetUserUuidResponse($user);
    }
}