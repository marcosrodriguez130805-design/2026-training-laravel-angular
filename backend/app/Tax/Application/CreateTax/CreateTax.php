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

    public function __invoke(string $name, int $percentage, string $restaurantUuid): CreateTaxResponse
{
    $restaurantId = Uuid::create($restaurantUuid);

    if ($this->repository->existsByNameAndRestaurant($name, $restaurantUuid)) {
        throw new TaxNameAlreadyExistsException($name);
    }

    $tax = Tax::dddCreate(
        $restaurantId,
        $name,
        $percentage
    );

    $this->repository->save($tax);

    return new CreateTaxResponse($tax);
}
}