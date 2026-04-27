<?php

namespace App\Zone\Application\CreateZone;

use App\Zone\Domain\Entity\Zone;

final class CreateZoneResponse
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
            'active'          => $this->zone->active(),
            'created_at'      => $this->zone->createdAt()->value()->format('Y-m-d H:i:s'),
            'updated_at'      => $this->zone->updatedAt()->value()->format('Y-m-d H:i:s'),
        ];
    }
}