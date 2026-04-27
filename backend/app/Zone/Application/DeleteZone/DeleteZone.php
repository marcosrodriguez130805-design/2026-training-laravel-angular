<?php

namespace App\Zone\Application\DeleteZone;

use App\Zone\Domain\Interfaces\ZoneRepositoryInterface;
use App\Zone\Domain\Exception\ZoneNotFoundException;
use App\Shared\Domain\ValueObject\Uuid;

final class DeleteZone
{
    public function __construct(
        private ZoneRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid, string $restaurantUuid): void
    {
        // 1. Verificamos si existe antes de intentar borrar
        $zone = $this->repository->findByUuid(Uuid::create($uuid), $restaurantUuid);

        if (!$zone) {
            throw new ZoneNotFoundException($uuid);
        }

        // 2. Ejecutamos el borrado
        $this->repository->delete($uuid);
    }
}