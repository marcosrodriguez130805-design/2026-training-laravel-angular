<?php

namespace App\Table\Application\ListTables;

use App\Table\Domain\Entity\Table;
use App\Table\Domain\Interfaces\TableRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class ListTables
{
    public function __construct(
        private TableRepositoryInterface $repository,
    ) {}

    public function __invoke(string $restaurantUuid): array
    {
        $uuid = Uuid::create($restaurantUuid);

        // El repositorio DEBE devolver un array de objetos Table
        $tables = $this->repository->listByRestaurant($uuid);

        return array_map(
            function(Table $table) { // Forzamos el tipo Table aquí para debuguear
                return (new ListTablesResponse($table))->toArray();
            },
            $tables
        );
    }
}