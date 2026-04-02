<?php

namespace App\User\Domain\Interfaces;

use App\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findByUuid(string $uuid): ?User;

    public function update(User $user): void;
    
    public function save(User $user): void;

    public function findAll(): array;

    public function findByEmail(string $email): ?User;

    public function deleteOrderLinesByUserId(string $userId): void;

    public function deleteOrdersByUserId(string $uuid): void;

    public function deleteSalesByUserId(string $uuid): void;

    public function deleteSalesLinesByUserId(string $uuid): void;
}