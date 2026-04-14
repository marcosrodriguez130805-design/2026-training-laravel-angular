<?php

namespace App\Zone\Application\UpdateZone;

use App\Zone\Domain\Entity\Zone;

final class UpdateZoneResponse
{
    public function __construct(
        private Zone $zone
    ) {}

    public function toArray(): array
    {
        return [
            'uuid'            => $this->zone->uuid()->value(),
            'restaurant_uuid' => $this->zone->restaurantUuid()->value(),
            'name'            => $this->zone->name(),
        ];
    }
}