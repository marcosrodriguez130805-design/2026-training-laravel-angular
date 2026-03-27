<?php

namespace App\Family\Application\ToggleFamilyActive;

use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class ToggleFamilyActive
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid): ToggleFamilyActiveResponse
    {
        $family = $this->repository->findByUuid(Uuid::create($uuid));

        if(!$family) {
            throw new \RuntimeException("Family not found with uuid: $uuid");
        }

        $family->toggleActive();

        $this->repository->update($family);

        return new ToggleFamilyActiveResponse($family);
    }
}