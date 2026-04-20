<?php

namespace App\Table\Application\CreateTable;

use App\Table\Domain\Entity\Table;
use App\Table\Domain\Interfaces\TableRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\DomainDateTime;
use Illuminate\Support\Str;

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
        
        $tableUuid = Uuid::create(Str::uuid()->toString());
        $now = DomainDateTime::now();

        // Creamos la entidad con el mismo patrón que Product
        $table = Table::dddCreate(
            $tableUuid,
            $restaurantUuid,
            $zoneUuid,
            $name,
            $now,
            $now
        );

        $this->repository->save($table);

        return new CreateTableResponse($table);
    }
}