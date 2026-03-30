<?php

namespace App\Family\Domain\Interfaces;

use App\Family\Domain\Entity\Family;
use App\Shared\Domain\ValueObject\Uuid;

interface FamilyRepositoryInterface
{
    public function save(Family $family):void;

    public function findByUuid(Uuid $uuid): ?Family;

    public function findAll(bool $onlyActive): array;

    public function update(Family $family): void;

    public function delete(Uuid $uuid): void;
}
