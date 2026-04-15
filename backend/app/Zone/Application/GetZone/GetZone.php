<?php

namespace App\Zone\Application\GetZone;

use App\Shared\Domain\ValueObject\Uuid; // 1. Importante para la conversión
use App\Zone\Domain\Interfaces\ZoneRepositoryInterface;

final class GetZone
{
    public function __construct(
        private ZoneRepositoryInterface $repository
    ) {}

    // 2. Añadimos el $restaurantUuid que viene del controlador
    public function __invoke(string $uuid, string $restaurantUuid): ?GetZoneResponse
    {
        // 3. Convertimos el string a objeto Uuid y pasamos el restaurantUuid
        $zone = $this->repository->findByUuid(
            Uuid::create($uuid), 
            $restaurantUuid
        );

        if (!$zone) {
            return null;
        }

        return new GetZoneResponse($zone);
    }
}