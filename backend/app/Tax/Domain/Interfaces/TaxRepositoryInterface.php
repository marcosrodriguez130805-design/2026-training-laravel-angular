<?php

namespace App\Tax\Domain\Interfaces;

use App\Tax\Domain\Entity\Tax;
use App\Shared\Domain\ValueObject\Uuid;

interface TaxRepositoryInterface
{
    public function save(Tax $tax): void;

    public function listAll(int $restaurantId): array;

    public function findByUuid(string $uuid): ?Tax;
}
