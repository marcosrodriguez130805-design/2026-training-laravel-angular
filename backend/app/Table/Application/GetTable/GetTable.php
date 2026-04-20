<?php

namespace App\Table\Application\GetTable;

use App\Table\Domain\Interfaces\TableRepositoryInterface;
use App\Table\Application\ListTables\ListTablesResponse;
use Exception;

final class GetTable
{
    public function __construct(
        private TableRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid): array
    {
        $table = $this->repository->findByUuid($uuid);

        if (!$table) {
            throw new Exception("Table not found", 404);
        }

        return (new ListTablesResponse($table))->toArray();
    }
}