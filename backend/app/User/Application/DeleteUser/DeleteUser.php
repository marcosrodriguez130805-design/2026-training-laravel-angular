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
    $user = $this->repository->findByUuid($uuid);

    if (!$user) {
        throw new \RuntimeException("User not found with uuid: $uuid");
    }

$this->repository->delete($uuid);                                    // 5️⃣ user                                    // 5️⃣ user                  
}
}