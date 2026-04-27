<?php

namespace App\Zone\Application\CreateZone;

use App\Zone\Domain\Entity\Zone;
use App\Zone\Domain\Interfaces\ZoneRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\DomainDateTime;
use Illuminate\Support\Str;

final class CreateZone
{
    public function __construct(
        private ZoneRepositoryInterface $repository
    ) {}

    public function __invoke(
        Uuid $restaurantUuid, 
        string $name,
        bool $active
    ): CreateZoneResponse {
        
        // Generamos los datos que faltan para la entidad
        $zoneUuid = Uuid::create(\Illuminate\Support\Str::uuid()->toString());
        $now = \App\Shared\Domain\ValueObject\DomainDateTime::now();

        // Usamos el método de creación de la entidad pasando el estado completo
        $zone = Zone::dddCreate(
            $zoneUuid,
            $restaurantUuid, 
            $name,
            $active,
            $now,
            $now
        );

        $this->repository->save($zone);

        return new CreateZoneResponse($zone);
    }
}