<?php

namespace App\Family\Application\DeleteFamily;

use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class DeleteFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid): void
    {
        $this->repository->delete(Uuid::create($uuid));
    }
}