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

    public function __invoke(Uuid $restaurantUuid): array
    {
        // Usamos el método que ya definimos en la interfaz antes
        $zones = $this->repository->listZones($restaurantUuid->value());

        return array_map(function (Zone $zone) {
            return new ListZonesResponse($zone);
        }, $zones);
    }
}