<?php

namespace App\Family\Domain\Interfaces;

use App\Family\Domain\Entity\Family;
use App\Shared\Domain\ValueObject\Uuid;

interface FamilyRepositoryInterface
{
    public function save(Family $family):void;

    public function findByUuid(Uuid $uuid, string $restaurantUuid): ?Family;

    public function findAll(string $restaurantUuid, bool $onlyActive = false): array;

    public function update(Family $family): void;

    public function delete(Uuid $uuid): void;
}
