<?php

namespace App\Family\Application\ToggleFamilyActive;

use App\Family\Domain\Exception\FamilyNotFoundException;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class ToggleFamilyActive
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid, string $restaurantUuid): ToggleFamilyActiveResponse
    {
        $family = $this->repository->findByUuid(Uuid::create($uuid), $restaurantUuid);

        if (!$family) {
            throw new FamilyNotFoundException($uuid);
        }

        $family->toggleActive();
        $this->repository->update($family);

        return new ToggleFamilyActiveResponse($family);
    }
}