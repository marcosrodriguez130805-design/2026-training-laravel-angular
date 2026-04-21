<?php

namespace App\Table\Application\GetTable;

use App\Table\Domain\Interfaces\TableRepositoryInterface;
use Exception;

final class GetTable
{
    public function __construct(
        private TableRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid): GetTableResponse // Devolvemos el OBJETO
{
    $table = $this->repository->findByUuid($uuid);

    if (!$table) {
        throw new \RuntimeException("Table not found with uuid: $uuid", 404);
    }

    return new GetTableResponse($table);
}
}