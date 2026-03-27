<?php

namespace App\Family\Domain\\;

use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class DeleteFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository;
    ) {}

    public function __invoke(string $uuid): void
    {
        $family = $this->repository->findByUuid(Uuid::create($uuid));

        if (!$family) {
            throw new \RuntimeException("Family not found with uuid: $uuid");
        }

        $this->repository->delete(Uuid::create($uuid));
    }
}