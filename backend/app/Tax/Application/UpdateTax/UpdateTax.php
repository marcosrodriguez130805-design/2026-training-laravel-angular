<?php

namespace App\Tax\Application\UpdateTax;

use App\Tax\Domain\Interfaces\TaxRepositoryInterface;

class UpdateTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(
        string $uuid, 
        string $restaurantUuid, 
        string $name, 
        int $percentage
    ): UpdateTaxResponse {
        
        $tax = $this->repository->findByUuid($uuid);

        if (!$tax) {
            throw new \Exception("Tax not found", 404);
        }

        // 1. Validaciones de formato
        if (empty(trim($name))) {
            throw new \Exception("Tax name cannot be empty", 400);
        }

        if ($percentage < 0) {
            throw new \Exception("Tax percentage cannot be negative", 400);
        }

        // 2. Validación de duplicados (excluyendo el actual)
        // Si esto devuelve TRUE, significa que OTRO registro ya tiene ese nombre
        if ($this->repository->existsByNameAndRestaurant($name, $restaurantUuid, $uuid)) {
            throw new \Exception("Has intentado poner un nombre que ya usa otro de tus impuestos", 409);
        }

        // 3. Actualización de la entidad y persistencia
        $tax->update($name, $percentage);
        $this->repository->update($tax);

        return new UpdateTaxResponse($tax);
    }
}