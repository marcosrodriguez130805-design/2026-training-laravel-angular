<?php

namespace App\Family\Application\CreateFamily;

use App\Family\Domain\Entity\Family;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\DomainDateTime; // Tu VO de fechas
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
        
        // Generamos los datos que faltan para la entidad
        $familyUuid = Uuid::create(Str::uuid()->toString());
        $now = DomainDateTime::now();

        // Usamos el método de creación de la entidad pasando el estado completo
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