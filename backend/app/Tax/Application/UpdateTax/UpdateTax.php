<?php

namespace App\Tax\Application\UpdateTax;

use App\Tax\Domain\Exception\TaxNotFoundException;
use App\Tax\Domain\Interfaces\TaxRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tax\Domain\Exception\TaxNameAlreadyExistsException;

class UpdateTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(
        Uuid $uuid,
        Uuid $restaurantUuid,
        string $name,
        int $percentage
    ): UpdateTaxResponse {
        $tax = $this->repository->findByUuid($uuid->value());

        if (!$tax) {
            throw new TaxNotFoundException($uuid->value());
        }

        if ($this->repository->existsByNameAndRestaurant($name, $restaurantUuid->value(), $uuid->value())) {
            throw new TaxNameAlreadyExistsException($name);
        }

        $tax->update($name, $percentage);
        $this->repository->update($tax);

        return new UpdateTaxResponse($tax);
    }
}