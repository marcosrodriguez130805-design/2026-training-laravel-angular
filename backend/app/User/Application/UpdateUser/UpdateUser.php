<?php

namespace App\User\Application\UpdateUser;

use App\User\Domain\Interfaces\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserName;
use App\User\Domain\ValueObject\PasswordHash;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Support\Facades\Hash;

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
        string $role,
        ?string $imageSrc
    ): UpdateUserResponse {

        $user = $this->repository->findByUuid($uuid);

        if (!$user) {
            throw new \RuntimeException("User not found with uuid: $uuid");
        }

        $user->updateName(UserName::create($name));
        $user->updateEmail($email);

        if ($password !== null && $password !== '') {
            $hashedPassword = Hash::make($password);
            $user->updatePassword(PasswordHash::create($hashedPassword));
        }

        if ($pin !== null) {
            $user->updatePin($pin);
        }

        $user->updateRole($role);

        if ($imageSrc !== null) {
            $user->updateImageSrc($imageSrc);
        }

        $this->repository->save($user);

        return new UpdateUserResponse($user);
    }
}