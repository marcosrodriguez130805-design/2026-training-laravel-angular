<?php

namespace App\Family\Application\ToggleFamilyActive;

use App\Family\Domain\Entity\Family;

class ToggleFamilyActiveResponse
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
            'created_at' => $this->family->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->family->updatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}