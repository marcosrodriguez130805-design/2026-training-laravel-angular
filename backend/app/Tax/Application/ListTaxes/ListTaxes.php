<?php

namespace App\Tax\Application\ListTaxes;

use App\Tax\Domain\Interfaces\TaxRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

final class ListTaxes
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(string $restaurantUuid): ListTaxesResponse
{
    $restaurantId = Uuid::create($restaurantUuid);
    $taxes = $this->repository->listTaxes($restaurantId);

    // Devolvemos el objeto Response, no el array
    return new ListTaxesResponse($taxes);
}
}