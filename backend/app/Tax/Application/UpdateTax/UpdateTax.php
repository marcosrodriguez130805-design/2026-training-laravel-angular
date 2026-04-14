<?php

namespace App\Tax\Application\UpdateTax;

use App\Tax\Domain\Interfaces\TaxRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid; // 👈 IMPORTANTE: Importar el VO

class UpdateTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(
        Uuid $uuid,           // 👈 Cambiado de string a Uuid
        Uuid $restaurantUuid, // 👈 Cambiado de string a Uuid
        string $name, 
        int $percentage
    ): UpdateTaxResponse {
        
        // 1. Buscamos el impuesto (pasamos el ->value() al repositorio)
        $tax = $this->repository->findByUuid($uuid->value());

        if (!$tax) {
            throw new \Exception("Tax not found", 404);
        }

        // 2. Validaciones básicas
        if (empty(trim($name))) {
            throw new \Exception("Tax name cannot be empty", 400);
        }

        if ($percentage < 0) {
            throw new \Exception("Tax percentage cannot be negative", 400);
        }

        // 3. Validación de duplicados (usamos ->value() para los IDs)
        if ($this->repository->existsByNameAndRestaurant($name, $restaurantUuid->value(), $uuid->value())) {
            throw new \Exception("Has intentado poner un nombre que ya usa otro de tus impuestos", 409);
        }

        // 4. Actualización de la entidad y persistencia
        $tax->update($name, $percentage);
        $this->repository->update($tax);

        return new UpdateTaxResponse($tax);
    }
}