<?php

namespace App\User\Application\UpdateUser;

use App\User\Domain\Interfaces\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserName;
use Illuminate\Support\Facades\Hash;
use App\User\Domain\ValueObject\PasswordHash;

class UpdateUser
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ) {}

    public function __invoke(
        string $uuid,
        string $name,
        string $email,
        ?string $password,
        ?string $pin,
        ?int $restaurantId,
        string $role,
        ?string $imageSrc
    ): UpdateUserResponse {

        $user = $this->repository->findByUuid($uuid);

        if (!$user) {
            throw new \RuntimeException("User not found with uuid: $uuid");
        }

        // 🔥 AQUÍ es donde usamos Value Objects
        $user->updateName(UserName::create($name));
        $user->updateEmail($email);

        if ($password !== null && $password !== '') {
            $hashedPassword = Hash::make($password);
            $user->updatePassword(PasswordHash::create($hashedPassword));
        }

        if ($pin !== null) {
            $user->updatePin($pin);
        }

        if ($restaurantId !== null) {
            $user->updateRestaurantId($restaurantId);
        }

        $user->updateRole($role);

        if ($imageSrc !== null) {
            $user->updateImageSrc($imageSrc);
        }

        $this->repository->save($user);

        return new UpdateUserResponse($user);
    }
}