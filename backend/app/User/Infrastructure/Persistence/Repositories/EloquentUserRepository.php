<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Persistence\Repositories;

use App\Shared\Domain\ValueObject\Uuid;
use App\User\Domain\Entity\User;
use App\User\Domain\Interfaces\UserRepositoryInterface;
use App\User\Infrastructure\Persistence\Models\EloquentUser;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EloquentUser $model,
    ) {}

    public function save(User $user): void
    {
        $model = EloquentUser::where('uuid', $user->id()->value())->first();

        if ($model) {
            $model->update([
                'restaurant_uuid' => $user->restaurantUuid()->value(),
                'role' => $user->role(),
                'image_src' => $user->imageSrc(),
                'name' => $user->name(),
                'email' => $user->email(),
                'password' => $user->passwordHash(),
                'pin' => $user->pin(),
                'created_at' => $user->createdAt()->value(),
                'updated_at' => $user->updatedAt()->value(),
            ]);

            return;
        }

        EloquentUser::create([
            'uuid' => $user->id()->value(),
            'restaurant_uuid' => $user->restaurantUuid()->value(),
            'role' => $user->role(),
            'image_src' => $user->imageSrc(),
            'name' => $user->name(),
            'email' => $user->email(),
            'password' => $user->passwordHash(),
            'pin' => $user->pin(),
            'created_at' => $user->createdAt()->value(),
            'updated_at' => $user->updatedAt()->value(),
        ]);
    }

    public function findByUuid(Uuid $uuid): ?User
    {
        $model = EloquentUser::where('uuid', $uuid->value())->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $model = EloquentUser::where('email', $email)->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function findAll(): array
    {
        return $this->model->newQuery()
            ->get()
            ->map(fn($model) => $this->toEntity($model))
            ->toArray();
    }

    public function update(User $user): void
    {
        EloquentUser::where('uuid', $user->id()->value())->update([
            'restaurant_uuid' => $user->restaurantUuid()->value(),
            'role' => $user->role(),
            'image_src' => $user->imageSrc(),
            'name' => $user->name(),
            'email' => $user->email(),
            'password' => $user->passwordHash(),
            'pin' => $user->pin(),
            'updated_at' => $user->updatedAt()->value(),
        ]);
    }

    public function delete(Uuid $uuid): void
    {
        EloquentUser::where('uuid', $uuid->value())->delete();
    }

    private function toEntity(EloquentUser $model): User
    {
        return User::fromPersistence(
            $model->uuid,
            $model->restaurant_uuid,
            $model->role,
            $model->name,
            $model->email,
            $model->password,
            $model->pin === null ? null : (string) $model->pin,
            $model->image_src,
            $model->created_at->toDateTimeImmutable(),
            $model->updated_at->toDateTimeImmutable()
        );
    }
}
