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

    public function findByUuid(string $uuid): ?User
    {
        $model = $this->model->newQuery()->where('uuid', $uuid)->first();

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByEmail(string $email): ?User
    {
        $model = $this->model->newQuery()->where('email', $email)->first();

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findAll(): array
    {
        return $this->model->newQuery()->get()->map(fn($model) => $this->toEntity($model))->toArray();
    }

    public function update(User $user): void
    {
        $this->model->newQuery()->where('uuid', $user->id()->value())->update([
            'restaurant_id' => $user->restaurantId(),
            'role'          => $user->role(),
            'image_src'     => $user->imageSrc(),
            'name'          => $user->name(),
            'email'         => $user->email(),
            'password'      => $user->passwordHash(),
            'pin'           => $user->pin(),
            'updated_at'    => $user->updatedAt()->value(),
        ]);
    }

    public function delete(string $uuid): void
    {
        $this->model->newQuery()->where('uuid', $uuid)->delete();
    }

    private function toEntity(EloquentUser $model): User
{
    return User::fromPersistence(
    $model->uuid,
    (int) $model->restaurant_id, // <--- ASEGÚRATE DE QUE TENGA EL (int)
    $model->role,
    $model->name,
    $model->email,
    $model->password,
    (string) $model->pin,
    $model->image_src,
    $model->created_at->toDateTimeImmutable(),
    $model->updated_at->toDateTimeImmutable()
);
}
}