<?php

namespace App\User\Application\LoginUser;

class LoginUserResponse
{
    public string $uuid;
    public string $restaurant_uuid;
    public string $role;
    public string $name;
    public string $email;
    public string $token;

    public function __construct(string $uuid, string $restaurant_uuid, string $role, string $name, string $email, string $token)
    {
        $this->uuid = $uuid;
        $this->restaurant_uuid = $restaurant_uuid;
        $this->role = $role;
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'restaurant_uuid' => $this->restaurant_uuid,
            'role' => $this->role,
            'name' => $this->name,
            'email' => $this->email,
            'token' => $this->token,
        ];
    }
}