<?php

namespace App\User\Application\CreateUser;

use App\User\Domain\Entity\User;

class CreateUserResponse
{
    public function __construct(
        private User $user,
    ) {}

    public function toArray(): array
    {
        return [
            'uuid' => $this->user->uuid()->value(),
            'restaurant_uuid' => $this->user->restaurantUuid()->value(),
            'role' => $this->user->role(),
            'image_src' => $this->user->imageSrc(),
            'name' => $this->user->name(),
            'email' => $this->user->email(),
            'pin' => $this->user->pin(),
            'created_at' => $this->user->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->user->updatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}