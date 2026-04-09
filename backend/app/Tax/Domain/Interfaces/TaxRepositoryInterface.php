<?php

namespace App\Tax\Domain\Interfaces;

use App\Tax\Domain\Entity\Tax;
use App\Shared\Domain\ValueObject\Uuid;

interface TaxRepositoryInterface
{
    public function save(Tax $tax): Tax;

    public function update(Tax $tax): Tax;

    public function listAll(string $restaurantUuid): array;

    public function findByUuid(string $uuid): ?Tax;

    public function findByUuidWithRestaurantUuid(string $uuid): ?array;

    public function existsByNameAndRestaurant(string $name, string $restaurantUuid, ?string $excludeUuid = null): bool;

    public function delete(string $uuid): void;

    public function existsByNameAndRestaurant(string $name, int $restaurantId, ?string $excludeUuid = null): bool;
}
