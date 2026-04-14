<?php

namespace App\Zone\Application\DeleteZone;

use App\Zone\Domain\Interfaces\ZoneRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

final class DeleteZone
{
    public function __construct(
        private ZoneRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid): void
    {
        // 1. Verificamos si existe antes de intentar borrar
        $zone = $this->repository->findByUuid($uuid);

        if (!$zone) {
            throw new \RuntimeException("Zone not found with uuid: $uuid");
        }

        // 2. Ejecutamos el borrado
        $this->repository->delete($uuid);
    }
}