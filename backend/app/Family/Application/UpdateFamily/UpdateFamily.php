<?php

namespace App\Family\Application\UpdateFamily;

use App\Family\Domain\Exception\FamilyNotFoundException;
use App\Family\Domain\Exception\FamilyAlreadyExistsException; // Importamos la excepción
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class UpdateFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}

    public function __invoke(string $uuid, string $restaurantUuid, string $name, bool $active): UpdateFamilyResponse
    {
        $family = $this->repository->findByUuid(Uuid::create($uuid), $restaurantUuid);

        if (!$family) {
            throw new FamilyNotFoundException($uuid);
        }

        // 1. Si el nombre ha cambiado, verificamos que el nuevo nombre no esté duplicado
        if ($family->name() !== $name) {
            $existingFamily = $this->repository->findByName($name, $restaurantUuid);
            
            if ($existingFamily) {
                throw new FamilyAlreadyExistsException($name);
            }
            
            $family->updateName($name);
        }

        // 2. Gestionar el estado activo/inactivo
        if ($family->active() !== $active) {
            $family->toggleActive();
        }

        // 3. Persistir cambios
        $this->repository->save($family);

        return new UpdateFamilyResponse($family);
    }
}