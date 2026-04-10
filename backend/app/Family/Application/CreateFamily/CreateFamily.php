<?php

namespace App\Family\Application\CreateFamily;

use App\Family\Domain\Entity\Family;
use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
// Importamos la interfaz del repositorio de restaurantes
use App\Restaurant\Domain\Interfaces\RestaurantRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class CreateFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
        // 🔥 INYECTAMOS el repositorio de restaurantes aquí
        private RestaurantRepositoryInterface $restaurantRepository 
    ) {}

    public function __invoke(
        Uuid $restaurantUuid, 
        string $name,
        bool $active
    ): CreateFamilyResponse {
        
        // Ahora $this->restaurantRepository ya existe y no dará error
        $restaurant = $this->restaurantRepository->findByUuid($restaurantUuid->value());
        
        if (!$restaurant) {
            throw new \RuntimeException("Restaurant not found with UUID: " . $restaurantUuid->value());
        }

        // Creamos la familia usando el ID numérico del restaurante
        $family = Family::create(
            $restaurant->id(), 
            $name,
            $active
        );

        $this->repository->save($family);

        return new CreateFamilyResponse($family);
    }
}