<?php

namespace App\User\Domain\Interfaces;

use App\Shared\Domain\ValueObject\Uuid;
use App\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findByUuid(Uuid $uuid): ?User;

    public function update(User $user): void;
    
    public function save(User $user): void;

    public function findAll(): array;

    public function findByEmail(string $email): ?User;

    public function delete(Uuid $uuid): void;

}