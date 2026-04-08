<?php

namespace App\Tax\Application\UpdateTax;

use App\Tax\Domain\Entity\Tax;

class UpdateTaxResponse
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
            'updated_at'    => $this->tax->updatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}