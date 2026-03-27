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
            'restaurant_id' => $this->family->restaurantId()->value(),
            'name' => $this->family->name(),
            'active' => $this->family->active(),
            'created_at' => $this->createdAt()->value()->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt()->value()->format('Y-m-d H:i:s'),
        ];
    }
}