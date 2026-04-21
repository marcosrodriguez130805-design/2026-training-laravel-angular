<?php

namespace App\Table\Application\DeleteTable;

use App\Table\Domain\Interfaces\TableRepositoryInterface;

class DeleteTable
{
    public function __construct(
        private TableRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid): void
    {
        $this->repository->delete($uuid);
    }
}