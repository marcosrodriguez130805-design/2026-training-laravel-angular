<?php

namespace App\User\Application\GetUserEmail;

use App\User\Domain\Entity\User;

class GetUserEmailResponse
{
    public function __construct(
        private User $user,
    ) {}

    public function toArray(): array
    {
        return [
            'uuid'          => $this->user->uuid()->value(),
            'restaurant_id' => $this->user->restaurantId(),
            'role'          => $this->user->role(),
            'name'          => $this->user->name(),
            'email'         => $this->user->email(),
            'image_src'     => $this->user->imageSrc(),
            'pin'           => $this->user->pin(),
            'created_at'    => $this->user->createdAt()->value()->format('Y-m-d H:i:s'),
            'updated_at'    => $this->user->updatedAt()->value()->format('Y-m-d H:i:s'),
        ];
    }
}