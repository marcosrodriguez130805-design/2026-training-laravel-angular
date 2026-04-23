<?php

namespace App\Family\Application\GetFamily;

use App\Family\Domain\Exception\FamilyNotFoundException;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class GetFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid, string $restaurantUuid): GetFamilyResponse
    {
        $family = $this->repository->findByUuid(Uuid::create($uuid), $restaurantUuid);

        if (!$family) {
            throw new FamilyNotFoundException($uuid);
        }

        return new GetFamilyResponse($family);
    }
}