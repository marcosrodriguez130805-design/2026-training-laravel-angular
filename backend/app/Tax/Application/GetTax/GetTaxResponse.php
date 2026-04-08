<?php

namespace App\Tax\Application\GetTax;

use App\Tax\Domain\Entity\Tax;

class GetTaxResponse
{
    public function __construct(
        private Tax $tax
    ) {}

    public function toArray(): array
    {
        return [
            'uuid'          => $this->tax->uuid()->value(),
            'restaurant_id' => $this->tax->restaurantId(),
            'name'          => $this->tax->name(),
            'percentage'    => $this->tax->percentage(),
            'created_at'    => $this->tax->createdAt()->format('Y-m-d H:i:s'),
            'updated_at'    => $this->tax->updatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}