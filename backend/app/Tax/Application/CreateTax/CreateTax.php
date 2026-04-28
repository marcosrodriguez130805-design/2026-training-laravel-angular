<?php

namespace App\Tax\Application\CreateTax;

use App\Tax\Domain\Entity\Tax;
use App\Tax\Domain\Interfaces\TaxRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tax\Domain\Exception\TaxNameAlreadyExistsException;

final class CreateTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(Uuid $restaurantUuid, string $name, int $percentage): CreateTaxResponse
    {
        if ($this->repository->existsByNameAndRestaurant($name, $restaurantUuid->value())) {
            throw new TaxNameAlreadyExistsException($name);
        }

        $tax = Tax::dddCreate(
            $restaurantUuid,
            $name,
            $percentage
        );

        $this->repository->save($tax);

        return new CreateTaxResponse($tax);
    }
}