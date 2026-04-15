<?php

namespace App\Zone\Application\ListZones;

use App\Zone\Domain\Interfaces\ZoneRepositoryInterface;
use App\Zone\Domain\Entity\Zone;
use App\Shared\Domain\ValueObject\Uuid;

final class ListZones
{
    public function __construct(
        private ZoneRepositoryInterface $repository
    ) {}

    public function __invoke(string $restaurantUuid): array
    {
    // Convertimos el string a Value Object para asegurar que es un UUID válido
        $uuid = Uuid::create($restaurantUuid);

    // Asegúrate de que tu repositorio tenga este método 'listAll' o 'listByRestaurant'
        $zones = $this->repository->listZones($uuid->value());

        return array_map(function (Zone $zone) {
            return (new ListZonesResponse($zone))->toArray(); // O como tengas el Response
        }, $zones);
    }
}