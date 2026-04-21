<?php

namespace App\Table\Application\UpdateTable;

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
            throw new \Exception("Table not found", 404);
        }

        $table->update($name);

        $this->repository->update($table);

        return new UpdateTableResponse($table);
    }
}