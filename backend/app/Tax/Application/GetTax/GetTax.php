<?php

namespace App\Tax\Application\GetTax;

use App\Tax\Domain\Interfaces\TaxRepositoryInterface;

class GetTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid, string $restaurantUuid): ?GetTaxResponse
    {
        $taxWithRestaurant = $this->repository->findByUuidWithRestaurantUuid($uuid);

        if (!$taxWithRestaurant) {
            return null;
        }

        // VALIDACIÓN CRUCIAL:
        // Comparamos el restaurante del impuesto con el del header
        if ($taxWithRestaurant['restaurant_uuid'] !== $restaurantUuid) {
            return null; // El impuesto no pertenece a este restaurante
        }

        return new GetTaxResponse(
            $taxWithRestaurant['tax'], 
            $taxWithRestaurant['restaurant_uuid']
        );
    }
}