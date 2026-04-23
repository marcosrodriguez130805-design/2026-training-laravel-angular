<?php

namespace App\Family\Application\UpdateFamily;

use App\Family\Domain\Exception\FamilyNotFoundException;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class UpdateFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid, string $name, bool $active): UpdateFamilyResponse
    {
        $family = $this->repository->findByUuid(Uuid::create($uuid));

        if (!$family) {
            throw new FamilyNotFoundException($uuid);
        }

        $family->updateName($name);

        if ($family->active() !== $active) {
            $family->toggleActive();
        }

        $this->repository->save($family);

        return new UpdateFamilyResponse($family);
    }
}