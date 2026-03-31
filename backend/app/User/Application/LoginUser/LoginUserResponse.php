<?php

namespace App\User\Application\LoginUser;

class LoginUserResponse
{
    public string $id;
    public string $name;
    public string $email;
    public string $token;

    public function __construct(string $id, string $name, string $email, string $token)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'token' => $this->token,
        ];
    }
}