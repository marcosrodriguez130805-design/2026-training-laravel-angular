<?php

namespace App\Tax\Application\ListTaxes;

use App\Tax\Domain\Entity\Tax;

class ListTaxesResponse
{
    public function __construct(
        private array $taxes // Recibimos el array que devuelve el repositorio
    ) {}

    public function toArray(): array
    {
        // Iteramos sobre cada objeto Tax para convertirlo en array
        return array_map(fn(Tax $tax) => [
            'uuid'            => $tax->uuid()->value(),
            'restaurant_uuid' => $tax->restaurantId()->value(),
            'name'            => $tax->name(),
            'percentage'      => $tax->percentage(),
        ], $this->taxes);
    }
}