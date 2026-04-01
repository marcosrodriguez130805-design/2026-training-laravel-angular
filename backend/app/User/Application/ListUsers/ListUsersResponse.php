<?php

namespace App\User\Application\ListUsers;

use App\User\Domain\Interfaces\UserRepositoryInterface;
use App\User\Domain\Entity\User;

class ListUsersResponse
{
    public function __construct(
        private User $user,
    ) {}

    public function toArray(): array
    {
        return [
            'uuid' => $this->user->uuid()->value(),
            'restaurant_id' => $this->user->restaurantId(),
            'role' => $this->user->role(),
            'image_src' => $this->user->imageSrc(),
            'name' => $this->user->name(),
            'email' => $this->user->email(),
            'created_at' => $this->user->createdAt()->value()->format('Y-m-d H:i:s'),
            'updated_at' => $this->user->updatedAt()->value()->format('Y-m-d H:i:s'),
        ];
    }
}