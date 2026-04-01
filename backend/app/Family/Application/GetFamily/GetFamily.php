<?php

namespace App\Family\Application\GetFamily;

use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class GetFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}
    
    public function __invoke(string $uuid): GetFamilyResponse
    {
        $family = $this->repository->findByUuid(Uuid::create($uuid));

        if (!$family) {
            throw new \RuntimeExeption("Family not found with uuid: $uuid");
        }

        return new GetFamilyResponse($family);
    }
}