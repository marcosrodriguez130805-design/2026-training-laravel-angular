<?php

namespace App\User\Application\UpdateUser;

use App\User\Domain\Entity\User;

class UpdateUserResponse
{
    private string $uuid;
    private \App\Shared\Domain\ValueObject\Uuid $restaurantId;
    private string $role;
    private string $name;
    private string $email;
    private ?string $pin;
    private ?string $imageSrc;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(User $user)
    {
        $this->uuid         = $user->id()->value();
        $this->restaurantId = $user->restaurantUuid();
        $this->role         = $user->role();
        $this->name         = $user->name();
        $this->email        = $user->email();
        $this->pin          = $user->pin();
        $this->imageSrc     = $user->imageSrc();
        $this->createdAt    = $user->createdAt()->format('Y-m-d H:i:s');
        $this->updatedAt    = $user->updatedAt()->format('Y-m-d H:i:s');
    }

    public function toArray(): array
    {
        return [
            'uuid'          => $this->uuid,
            'restaurant_uuid' => $this->restaurantId->value(),
            'role'          => $this->role,
            'name'          => $this->name,
            'email'         => $this->email,
            'pin'           => $this->pin,
            'image_src'     => $this->imageSrc,
            'created_at'    => $this->createdAt,
            'updated_at'    => $this->updatedAt,
        ];
    }
}