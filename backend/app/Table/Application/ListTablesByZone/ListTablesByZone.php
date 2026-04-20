<?php

namespace App\Table\Application\ListTablesByZone;

use App\Table\Domain\Interfaces\TableRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Table\Application\ListTablesByZone\ListTablesByZoneResponse;
class ListTablesByZone
{
    public function __construct(
        private TableRepositoryInterface $repository
    ) {}

    public function __invoke(string $restaurantUuid, string $zoneUuid): array
    {
        // Validamos ambos UUIDs
        $resId = Uuid::create($restaurantUuid);
        $zoneId = Uuid::create($zoneUuid);

        $tables = $this->repository->findByZone($resId->value(), $zoneId->value());

        return array_map(
            fn($table) => (new ListTablesByZoneResponse($table))->toArray(),
            $tables
        );
    }
}