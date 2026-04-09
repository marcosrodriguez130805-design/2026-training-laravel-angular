<?php

namespace App\Tax\Application\CreateTax;

use App\Shared\Domain\ValueObject\Uuid;

class CreateTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(string $restaurantUuid, string $name, int $percentage): CreateTaxResponse
    {
        $restaurantId = Uuid::create($restaurantUuid);

        // Validaciones
        if (empty(trim($name))) {
            throw new \Exception("Tax name cannot be empty", 400);
        }
        if ($percentage < 0) {
            throw new \Exception("Tax percentage cannot be negative", 400);
        }
        if ($this->repository->existsByNameAndRestaurant($name, $restaurantUuid)) {
            throw new \Exception("Tax name already exists for this restaurant", 409);
        }

        $tax = Tax::dddCreate($restaurantId, $name, $percentage);
        $this->repository->save($tax);
        return new CreateTaxResponse($tax);
    }
}