<?php

namespace App\Tax\Application\GetTax;

use App\Tax\Domain\Interfaces\TaxRepositoryInterface;

class GetTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid): ?GetTaxResponse
    {
        $taxWithRestaurant = $this->repository->findByUuidWithRestaurantUuid($uuid);

        if (!$taxWithRestaurant) {
            return null;
        }

        return new GetTaxResponse(
            $taxWithRestaurant['tax'], 
            $taxWithRestaurant['restaurant_uuid']
        );
    }
}