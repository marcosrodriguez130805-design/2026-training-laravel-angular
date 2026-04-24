<?php

namespace App\Table\Application\GetTable;

use App\Table\Domain\Exception\TableNotFoundException;
use App\Table\Domain\Interfaces\TableRepositoryInterface;

final class GetTable
{
    public function __construct(
        private TableRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid): GetTableResponse
    {
        $table = $this->repository->findByUuid($uuid);

        if (!$table) {
            throw new TableNotFoundException($uuid);
        }

        return new GetTableResponse($table);
    }
}