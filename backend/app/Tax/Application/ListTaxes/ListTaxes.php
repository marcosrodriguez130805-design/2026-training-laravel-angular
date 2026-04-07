<?php

namespace App\Tax\Application\ListTaxes;

use App\Tax\Domain\Interfaces\TaxRepositoryInterface;

class ListTaxes
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(int $restaurantId): array
    {
        $taxes = $this->repository->listAll($restaurantId);

        // AQUÍ ESTÁ EL TRUCO: 
        // Convertimos cada Tax (Entidad) en un ListTaxesResponse (DTO)
        return array_map(
            fn($tax) => new ListTaxesResponse($tax), 
            $taxes
        );
    }
}