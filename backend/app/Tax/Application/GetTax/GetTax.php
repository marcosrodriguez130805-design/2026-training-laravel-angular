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
        $tax = $this->repository->findByUuid($uuid);

        if (!$tax) {
            return null;
        }

        return new GetTaxResponse($tax);
    }
}