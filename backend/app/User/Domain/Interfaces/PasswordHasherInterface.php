<?php

namespace App\User\Domain\Interfaces;

interface PasswordHasherInterface
{
    public function hash(string $plainPassword): string;
    public function check(string $plainPassword, string $hashedPassword): bool;
}
