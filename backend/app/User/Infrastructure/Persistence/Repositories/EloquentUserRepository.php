<?php

namespace App\User\Infrastructure\Persistence\Repositories;

use App\User\Domain\Entity\User;
use App\User\Domain\Interfaces\UserRepositoryInterface;
use App\User\Infrastructure\Persistence\Models\EloquentUser;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EloquentUser $model,
    ) {}

    public function save(User $user): void
    {
        $this->model->newQuery()->updateOrCreate(
            ['uuid' => $user->id()->value()],
            [
                'restaurant_id' => $user->restaurantId(),
                'role'          => $user->role(),
                'image_src'     => $user->imageSrc(),
                'name'          => $user->name(),
                'email'         => $user->email()->value(),
                'password'      => $user->passwordHash(),
                'pin'           => $user->pin(),
                'created_at'    => $user->createdAt()->value(),
                'updated_at'    => $user->updatedAt()->value(),
            ]
        );
    }

    public function findById(string $id): ?User
    {
        $model = $this->model->newQuery()->where('uuid', $id)->first();

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByEmail(string $email): ?EloquentUser
    {
        return $this->model->newQuery()->where('email', $email)->first();
    }

    private function toEntity(EloquentUser $model): User
    {
        return User::fromPersistence(
            $model->uuid,
            $model->name,
            $model->email,
            $model->password,
            $model->created_at->toDateTimeImmutable(),
            $model->updated_at->toDateTimeImmutable(),
        );
    }
}