<?php

namespace App\Family\Application\CreateFamily;

use App\Family\Domain\Entity\Family;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class CreateFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}

    public function ___invoke(string $restaurantId, string $name, bool $active): CreateFamilyResponse
    {
        $family = Family::dddCreate(
            Uuid::create($restaurantId), 
            $name, 
            $active
        );

        $this->repository->save($family);

        return new CreateFamilyResponse($family);
    }
}