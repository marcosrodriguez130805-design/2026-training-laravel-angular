<?php

namespace App\Family\Application\GetFamily;

use App\Family\Domain\Entity\Family;

class GetFamilyResponse
{
    public function __construct(
        private Family $family,
    ) {}

    public function toArray(): array
    {
        return [
            'uuid' => $this->family->uuid()->value(),
            'restaurant_uuid' => $this->family->restaurantId()->value(),
            'name' => $this->family->name(),
            'active' => $this->family->active(),
        ];
    }
}