<?php

namespace App\Zone\Application\CreateZone;

use App\Zone\Domain\Entity\Zone;
use App\Zone\Domain\Interfaces\ZoneRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

final class CreateZone
{
    public function __construct(
        private ZoneRepositoryInterface $repository
    ) {}

    public function __invoke(
        Uuid $restaurantUuid,
        string $name
    ): CreateZoneResponse {
        
        // Delegamos la creación a la entidad (Pureza de Dominio)
        $zone = Zone::dddCreate(
            $restaurantUuid,
            $name
        );

        $this->repository->save($zone);

        return new CreateZoneResponse($zone);
    }
}