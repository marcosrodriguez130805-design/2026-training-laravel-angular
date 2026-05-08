<?php

namespace App\Family\Application\CreateFamily;

use App\Family\Domain\Entity\Family;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Family\Domain\Exception\FamilyAlreadyExistsException; // Importamos la nueva excepción
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\DomainDateTime;
use Illuminate\Support\Str;

final class CreateFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository
    ) {}

    public function __invoke(
        Uuid $restaurantUuid, 
        string $name,
        bool $active
    ): CreateFamilyResponse {
        
        // 1. Validar si ya existe una familia con ese nombre en este restaurante
        $existingFamily = $this->repository->findByName($name, $restaurantUuid->value());

        if ($existingFamily) {
            throw new FamilyAlreadyExistsException($name);
        }

        // 2. Generamos los datos que faltan para la entidad
        $familyUuid = Uuid::create(Str::uuid()->toString());
        $now = DomainDateTime::now();

        // 3. Usamos el método de creación de la entidad pasando el estado completo
        $family = Family::dddCreate(
            $familyUuid,
            $restaurantUuid, 
            $name,
            $active,
            $now,
            $now
        );

        $this->repository->save($family);

        return new CreateFamilyResponse($family);
    }
}