<?php

namespace App\Tax\Application\GetTax;

use App\Tax\Domain\Exception\TaxNotFoundException;
use App\Tax\Domain\Interfaces\TaxRepositoryInterface;

class GetTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid, string $restaurantUuid): GetTaxResponse
    {
        $taxWithRestaurant = $this->repository->findByUuidWithRestaurantUuid($uuid);

        if (!$taxWithRestaurant) {
            throw new TaxNotFoundException($uuid);
        }

        if ($taxWithRestaurant['restaurant_uuid'] !== $restaurantUuid) {
            throw new TaxNotFoundException($uuid);
        }

        return new GetTaxResponse(
            $taxWithRestaurant['tax'],
            $taxWithRestaurant['restaurant_uuid']
        );
    }
}