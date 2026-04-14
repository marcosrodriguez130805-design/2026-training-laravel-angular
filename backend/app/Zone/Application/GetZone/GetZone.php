<?php

namespace App\Zone\Application\GetZone;

use App\Zone\Domain\Interfaces\ZoneRepositoryInterface;

final class GetZone
{
    public function __construct(
        private ZoneRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid): ?GetZoneResponse
    {
        // Usamos el findByUuid que ya tenemos en la interfaz
        $zone = $this->repository->findByUuid($uuid);

        if (!$zone) {
            return null;
        }

        return new GetZoneResponse($zone);
    }
}