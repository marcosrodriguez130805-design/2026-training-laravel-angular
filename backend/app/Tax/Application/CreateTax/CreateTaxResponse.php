<?php

namespace App\Tax\Application\CreateTax;

use App\Tax\Domain\Entity\Tax;

class CreateTaxResponse
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
            'percentage'    => $this->tax->percentage(), // Recordamos: tipo INT
        ];
    }
}