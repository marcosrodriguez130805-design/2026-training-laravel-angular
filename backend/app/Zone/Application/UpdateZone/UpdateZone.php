<?php

namespace App\Zone\Application\UpdateZone;

use App\Zone\Domain\Interfaces\ZoneRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

final class UpdateZone
{
    public function __construct(
        private ZoneRepositoryInterface $repository
    ) {}

    public function __invoke(
        string $uuid,
        string $name
    ): UpdateZoneResponse {
        // Usamos el método findByUuid que ya tienes en el repo
        $zone = $this->repository->findByUuid($uuid);

        if (!$zone) {
            throw new \Exception("Zone not found", 404);
        }

        // La entidad Zone ya tiene su método updateName o update
        $zone->update($name);

        // Persistimos el cambio
        $this->repository->update($zone);

        return new UpdateZoneResponse($zone);
    }
}