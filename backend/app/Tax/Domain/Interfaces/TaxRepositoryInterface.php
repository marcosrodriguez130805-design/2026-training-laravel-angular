<?php

namespace App\Tax\Domain\Interfaces;

use App\Tax\Domain\Entity\Tax;
use App\Shared\Domain\ValueObject\Uuid;

interface TaxRepositoryInterface
{
    public function save(Tax $tax): Tax;

    public function update(Tax $tax): Tax;

    public function listAll(int $restaurantId): array;

    public function findByUuid(string $uuid): ?Tax;

    public function delete(string $uuid): void;
}
