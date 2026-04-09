<?php

namespace App\Tax\Application\ListTaxes;

use App\Tax\Domain\Interfaces\TaxRepositoryInterface;

class ListTaxes
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(string $restaurantUuid): array
    {
        $taxes = $this->repository->listAll($restaurantUuid);

        return array_map(
            fn(Tax $tax) => new ListTaxesResponse($tax, $restaurantUuid),
            $taxes
        );
    }
}