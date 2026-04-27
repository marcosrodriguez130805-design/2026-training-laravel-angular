<?php

namespace App\Zone\Application\ToggleZoneActive;

use App\Zone\Domain\Interfaces\ZoneRepositoryInterface;
use App\Zone\Domain\Exception\ZoneNotFoundException;
use App\Shared\Domain\ValueObject\Uuid;

final class ToggleZoneActive
{
    public function __construct(
        private ZoneRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid, string $restaurantUuid): ToggleZoneActiveResponse
    {
        $zone = $this->repository->findByUuid(Uuid::create($uuid), $restaurantUuid);

        if (!$zone) {
            throw new ZoneNotFoundException($uuid);
        }

        $zone->toggleActive();

        $this->repository->update($zone);

        return new ToggleZoneActiveResponse($zone);
    }
}