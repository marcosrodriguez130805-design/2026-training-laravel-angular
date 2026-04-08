<?php

namespace App\Tax\Application\DeleteTax;

use App\Tax\Domain\Interfaces\TaxRepositoryInterface;

class DeleteTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(string $uuid): void
    {
        // Podrías validar si existe antes para lanzar una excepción 404
        $this->repository->delete($uuid);
    }
}