<?php

namespace App\Tax\Application\UpdateTax;

use App\Tax\Domain\Interfaces\TaxRepositoryInterface;

class UpdateTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid, int $restaurantId, string $name, int $percentage): UpdateTaxResponse
    {
        $tax = $this->repository->findByUuid($uuid);

        if (!$tax) {
            throw new \Exception("Tax not found", 404);
        }

        $tax->update($name, $percentage);

        $this->repository->update($tax);

        return new UpdateTaxResponse($tax);
    }
}