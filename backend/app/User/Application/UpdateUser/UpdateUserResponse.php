<?php

namespace App\User\Application\UpdateUser;

use App\User\Domain\Entity\User;

class UpdateUserResponse
{
    private string $uuid; // <-- agregar
    private int $restaurantId;
    private string $role;
    private string $name;
    private string $email;
    private string $pin;
    private string $imageSrc;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(User $user)
    {
        $this->uuid         = $user->id()->value(); // si id() devuelve un ValueObject UUID
        $this->restaurantId = $user->restaurantId();
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
            'restaurant_id' => $this->restaurantId,
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