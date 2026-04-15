<?php

namespace App\Family\Application\GetFamily;

use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class GetFamily
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}
    
    // 1. Añadimos el segundo parámetro
    public function __invoke(string $uuid, string $restaurantUuid): GetFamilyResponse
    {
        // 2. Pasamos ambos al repositorio (el ID del restaurante como string)
        $family = $this->repository->findByUuid(Uuid::create($uuid), $restaurantUuid);

        if (!$family) {
            // Un pequeño tip: 'RuntimeException' lleva una 'c' antes de la 't'
            throw new \RuntimeException("Family not found with uuid: $uuid");
        }

        return new GetFamilyResponse($family);
    }
}