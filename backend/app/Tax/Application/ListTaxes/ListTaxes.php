<?php

namespace App\Tax\Application\ListTaxes;

use App\Tax\Domain\Interfaces\TaxRepositoryInterface;
// 1. IMPORTA LA ENTIDAD DE DOMINIO
use App\Tax\Domain\Entity\Tax; 

class ListTaxes
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(string $restaurantUuid): array
    {
        $taxes = $this->repository->listAll($restaurantUuid);

        return array_map(
            // Ahora PHP sabe que este Tax es el de Domain\Entity\Tax
            fn(Tax $tax) => new ListTaxesResponse($tax, $restaurantUuid),
            $taxes
        );
    }
}