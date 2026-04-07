<?php

namespace App\Tax\Application\CreateTax;

use App\Tax\Domain\Entity\Tax;
use App\Tax\Domain\Interfaces\TaxRepositoryInterface;

class CreateTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(int $restaurantId, string $name, int $percentage): CreateTaxResponse
    {
        // 1. Creamos la entidad siguiendo el DATA_MODEL (restaurant_id, name, percentage)
        $tax = Tax::dddCreate(
            $restaurantId,
            $name,
            $percentage
        );

        // 2. Persistencia
        $this->repository->save($tax);

        return new CreateTaxResponse($tax);
    }
}