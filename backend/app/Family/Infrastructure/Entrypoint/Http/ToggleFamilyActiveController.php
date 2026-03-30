<?php

namespace App\Family\Application\ToggleFamilyActive;

use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Family\Application\UpdateFamily\UpdateFamilyResponse;
use App\Shared\Domain\ValueObject\Uuid;

class ToggleFamilyActive
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid): UpdateFamilyResponse
    {
        $family = $this->repository->findByUuid(new Uuid($uuid));

        if (!$family) {
            throw new \Exception("Family not found");
        }

        // Cambiamos solo el estado activo
        $family->toggleActive();

        $this->repository->update($family);

        return new UpdateFamilyResponse($family);
    }
}