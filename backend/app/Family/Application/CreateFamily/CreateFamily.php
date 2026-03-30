<?php

namespace App\Family\Application\CreateFamily;

use App\Family\Domain\Entity\Family;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;

class CreateFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}

    public function __invoke(int $restaurantId, string $name, bool $active): CreateFamilyResponse
    {
        $family = Family::dddCreate(
            (int) $restaurantId, 
            $name, 
            $active
        );

        $this->repository->save($family);

        return new CreateFamilyResponse($family);
    }
}