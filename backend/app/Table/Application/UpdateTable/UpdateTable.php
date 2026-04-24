<?php

namespace App\Table\Application\UpdateTable;

use App\Table\Domain\Exception\TableNotFoundException;
use App\Table\Domain\Interfaces\TableRepositoryInterface;

class UpdateTable
{
    public function __construct(
        private TableRepositoryInterface $repository
    ) {}

    public function __invoke(
        string $uuid,
        string $name
    ): UpdateTableResponse {
        $table = $this->repository->findByUuid($uuid);

        if (!$table) {
            throw new TableNotFoundException($uuid);
        }

        $table->update($name);
        $this->repository->update($table);

        return new UpdateTableResponse($table);
    }
}