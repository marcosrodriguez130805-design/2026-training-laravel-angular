<?php

namespace App\User\Application\UpdateUser;

use App\User\Domain\Interfaces\UserRepositoryInterface;

class UpdateUser
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {}

    public function __invoke(
        string $uuid,
        string $name,
        string $email,
        ?string $password = null,
        ?string $pin = null,
        ?int $restaurantId = null,
    ): UpdateUserResponse
    {
        // Ahora el UUID se usa directamente como string
        $user = $this->repository->findByUuid($uuid);
        if (!$user) {
            throw new \RuntimeException("User not found with uuid: $uuid");
        }

        $user->updateName($name);
        $user->updateEmail($email);

        if ($password !== null && $password !== '') {
            $user->updatePassword($password);
        }

        if ($pin !== null) {
            $user->updatePin($pin);
        }

        if ($restaurantId !== null) {
            $user->updateRestaurantId($restaurantId);
        }

        $this->repository->save($user);

        return new UpdateUserResponse($user);
    }
}