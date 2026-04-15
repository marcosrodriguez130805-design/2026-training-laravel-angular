<?php

namespace App\Table\Application\CreateTable;

use App\Table\Domain\Entity\Table;
use App\Table\Domain\Interfaces\TableRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

final class CreateTable
{
    public function __construct(
        private TableRepositoryInterface $repository
    ) {}

    public function __invoke(
        Uuid $restaurantUuid,
        Uuid $zoneUuid,
        string $name
    ): CreateTableResponse {
        // Creamos la entidad usando el método dddCreate (que genera el UUID)
        $table = Table::dddCreate($restaurantUuid, $zoneUuid, $name);

        // Persistimos
        $this->repository->save($table);

        return new CreateTableResponse($table);
    }
}