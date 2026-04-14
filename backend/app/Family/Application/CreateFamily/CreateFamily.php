<?php

namespace App\Family\Application\CreateFamily;

use App\Family\Domain\Entity\Family;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

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
        
        // Creamos la familia directamente con el UUID del restaurante
        // No necesitamos el RestaurantRepository porque la entidad Family
        // ahora debería aceptar el Uuid en su método de creación.
        $family = Family::dddCreate(
            $restaurantUuid, 
            $name,
            $active
        );

        $this->repository->save($family);

        return new CreateFamilyResponse($family);
    }
}